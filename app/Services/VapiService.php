<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VapiService
{
    private $assistantId;
    private $apiKey;
    private $webhookUrl;
    private $baseUrl = 'https://api.vapi.ai';

    public function __construct()
    {
        $this->assistantId = Setting::where('key', 'vapi_assistant_id')->value('value') ?: '5a05fa0e-87e8-43cc-969e-4c5c469670de';
        $this->apiKey = Setting::where('key', 'vapi_key')->value('value') ?: '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
        $this->webhookUrl = Setting::where('key', 'vapi_webhook_url')->value('value') ?: url('/api/vapi/webhook');
    }

    /**
     * Synchronize the assistant configuration, tools, and enums with Vapi.ai
     * 
     * @param string|null $prompt If null, fetches current prompt from Vapi
     * @param string|null $welcomeMessage If null, fetches current message from Vapi
     * @return bool Success or failure
     */
    public function syncAssistantConfig($prompt = null, $welcomeMessage = null)
    {
        try {
            // 1. Fetch current assistant data if prompt or welcomeMessage are null
            $getCurrent = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/assistant/{$this->assistantId}");

            if ($getCurrent->failed()) {
                Log::error('VapiService: Failed to fetch assistant data: ' . $getCurrent->status() . ' - ' . $getCurrent->body());
                return false;
            }

            $assistant = $getCurrent->json();
            $modelConfig = $assistant['model'] ?? [];
            
            $finalPrompt = $prompt ?: ($modelConfig['messages'][0]['content'] ?? '');
            $finalWelcome = $welcomeMessage ?: ($assistant['firstMessage'] ?? '');

            // 2. Sync Tools (get_assistance_types and save_ticket)
            $toolsListResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->get("{$this->baseUrl}/tool");
            $allTools = $toolsListResponse->successful() ? $toolsListResponse->json() : [];

            $getAssistanceToolId = null;
            $saveTicketToolId = null;

            foreach ($allTools as $tool) {
                $name = $tool['function']['name'] ?? '';
                if ($name === 'get_assistance_types') $getAssistanceToolId = $tool['id'];
                if ($name === 'save_ticket') $saveTicketToolId = $tool['id'];
            }

            // Prepare department names for Enums (Ensure consistent case or whatever is in DB)
            $departments = Department::where('is_active', true)->pluck('name')->toArray();
            if (empty($departments)) {
                $departments = ['Generico'];
            }

            $webhookUrl = $this->webhookUrl;

            // Configurazione Tool: get_assistance_types
            $getAssistanceConfig = [
                'type' => 'function',
                'function' => [
                    'name' => 'get_assistance_types',
                    'description' => 'Ottiene l\'elenco dei reparti di assistenza disponibili nel sistema per smistare correttamente il ticket.',
                    'parameters' => [
                        'type' => 'object', 
                        'properties' => (object)[]
                    ]
                ],
                'server' => ['url' => $webhookUrl]
            ];

            $getAssistanceToolId = $this->upsertTool($getAssistanceToolId, $getAssistanceConfig);

            // Configurazione Tool: save_ticket (Dinamico con Enums aggiornati)
            $saveTicketConfig = [
                'type' => 'function',
                'messages' => [['type' => 'request-start', 'content' => 'Sto salvando il tuo ticket...', 'blocking' => false]],
                'function' => [
                    'name' => 'save_ticket',
                    'description' => 'Salva un ticket di assistenza nel database locale.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'assistance_type' => [
                                'type' => 'string',
                                'description' => 'Il nome esatto del reparto scelto tra quelli disponibili (ottenuti da get_assistance_types).',
                                'enum' => $departments // AUTOMATIC SYNC OF ENUMS
                            ],
                            'company_name' => ['type' => 'string', 'description' => 'Il nome dell\'azienda che chiama'],
                            'customer_name' => ['type' => 'string', 'description' => 'Nome e Cognome della persona'],
                            'email' => ['type' => 'string', 'description' => 'Indirizzo e-mail della persona'],
                            'phone' => [
                                'type' => 'string', 
                                'description' => 'Numero di telefono NUMERICO del chiamante. NON inserire nomi o cognomi in questo campo.'
                            ],
                            'description' => ['type' => 'string', 'description' => 'Descrizione dettagliata del problema']
                        ],
                        'required' => ['assistance_type', 'company_name', 'customer_name', 'phone', 'description']
                    ]
                ],
                'server' => ['url' => $webhookUrl]
            ];

            $saveTicketToolId = $this->upsertTool($saveTicketToolId, $saveTicketConfig);

            // 2.3 Configurazione Tool: check_availability
            $checkAvailabilityToolId = null;
            foreach ($allTools as $tool) {
                if (($tool['function']['name'] ?? '') === 'check_availability') $checkAvailabilityToolId = $tool['id'];
            }
            $checkAvailabilityConfig = [
                'type' => 'function',
                'function' => [
                    'name' => 'check_availability',
                    'description' => 'Verifica la disponibilità di orari liberi per un appuntamento in un determinato reparto e data.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'department_name' => ['type' => 'string', 'enum' => $departments, 'description' => 'Il nome del reparto scelto.'],
                            'date' => ['type' => 'string', 'description' => 'La data desiderata (formato YYYY-MM-DD). Se l\'utente dice "domani", converti in data reale.']
                        ],
                        'required' => ['department_name', 'date']
                    ]
                ],
                'server' => ['url' => $webhookUrl]
            ];
            $checkAvailabilityToolId = $this->upsertTool($checkAvailabilityToolId, $checkAvailabilityConfig);

            // 2.4 Configurazione Tool: book_appointment
            $bookAppointmentToolId = null;
            foreach ($allTools as $tool) {
                if (($tool['function']['name'] ?? '') === 'book_appointment') $bookAppointmentToolId = $tool['id'];
            }
            $bookAppointmentConfig = [
                'type' => 'function',
                'messages' => [['type' => 'request-start', 'content' => 'Sto verificando e fissando il tuo appuntamento...', 'blocking' => false]],
                'function' => [
                    'name' => 'book_appointment',
                    'description' => 'Prenota un appuntamento in agenda per il reparto scelto.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'department_name' => ['type' => 'string', 'enum' => $departments, 'description' => 'Il nome del reparto scelto.'],
                            'customer_name' => ['type' => 'string', 'description' => 'Nome e Cognome del cliente.'],
                            'phone' => ['type' => 'string', 'description' => 'Numero di telefono del cliente.'],
                            'date' => ['type' => 'string', 'description' => 'Data dell\'appuntamento (YYYY-MM-DD).'],
                            'time' => ['type' => 'string', 'description' => 'Ora dell\'appuntamento (HH:MM).'],
                            'reason' => ['type' => 'string', 'description' => 'Breve motivo dell\'appuntamento.']
                        ],
                        'required' => ['department_name', 'customer_name', 'phone', 'date', 'time', 'reason']
                    ]
                ],
                'server' => ['url' => $webhookUrl]
            ];
            $bookAppointmentToolId = $this->upsertTool($bookAppointmentToolId, $bookAppointmentConfig);

            // 3. Update Assistant
            $basePrompt = $finalPrompt;
            $instr = "GESTIONE AGENDA: Oltre all'apertura ticket, puoi fissare APPUNTAMENTI.\n" .
                     "1. Per fissare un appuntamento, devi PRIMA conoscere il reparto (get_assistance_types).\n" .
                     "2. Una volta noto il reparto e il giorno, USA 'check_availability' per vedere gli orari liberi e riferiscili all'utente.\n" .
                     "3. Quando l'utente sceglie un orario, usa 'book_appointment' per confermare.\n" .
                     "4. Non inventare orari: proponi solo quelli che rientrano negli orari di lavoro del reparto e che sono liberi.\n\n" .
                     "RELAZIONE CAMPI EXTRA: Assicurati sempre di avere il NOME e il TELEFONO corretti.\n\n";
            
            if (strpos($basePrompt, 'book_appointment') === false) {
                $basePrompt = $instr . $basePrompt;
            }

            $modelConfig['messages'] = [['role' => 'system', 'content' => $basePrompt]];
            $modelConfig['toolIds'] = array_filter([$getAssistanceToolId, $saveTicketToolId, $checkAvailabilityToolId, $bookAppointmentToolId]);
            unset($modelConfig['tools']);

            $payload = [
                'model' => $modelConfig,
                'firstMessage' => $finalWelcome,
                'serverUrl' => $webhookUrl,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->patch("{$this->baseUrl}/assistant/{$this->assistantId}", $payload);

            if ($response->failed()) {
                Log::error('VapiService: Error updating assistant: ' . $response->status() . ' - ' . $response->body());
                return false;
            }

            Log::info('VapiService: Assistant configuration synchronized successfully.');
            return true;

        } catch (\Exception $e) {
            Log::error('VapiService Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper to Create or Update a Tool
     */
    private function upsertTool($toolId, $config)
    {
        if (!$toolId) {
            $res = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->post("{$this->baseUrl}/tool", $config);
            if ($res->failed()) {
                Log::error('VapiService: Error creating tool: ' . $res->status() . ' - ' . $res->body());
                return null;
            }
            return $res->json()['id'];
        } else {
            // PATCH must not send read-only fields
            unset($config['type'], $config['id'], $config['orgId'], $config['createdAt'], $config['updatedAt']);
            
            $res = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->patch("{$this->baseUrl}/tool/{$toolId}", $config);
            if ($res->failed()) {
                Log::error("VapiService: Error updating tool {$toolId}: " . $res->status() . ' - ' . $res->body());
            }
            return $toolId;
        }
    }
}
