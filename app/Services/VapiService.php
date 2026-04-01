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
    private $baseUrl = 'https://api.vapi.ai';

    public function __construct()
    {
        $this->assistantId = Setting::where('key', 'vapi_assistant_id')->value('value') ?: '5a05fa0e-87e8-43cc-969e-4c5c469670de';
        $this->apiKey = Setting::where('key', 'vapi_key')->value('value') ?: '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
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
                Log::error('VapiService: Failed to fetch assistant data: ' . $getCurrent->body());
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

            // Prepare department names for Enums
            $departments = Department::where('is_active', true)->pluck('name')->toArray();
            if (empty($departments)) {
                $departments = ['Generico'];
            }

            $webhookUrl = url('/api/vapi/webhook');

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

            $this->upsertTool($getAssistanceToolId, $getAssistanceConfig);

            // Configurazione Tool: save_ticket (Dinamico con Enums aggiornati)
            $saveTicketConfig = [
                'type' => 'function',
                'messages' => [['type' => 'request-start', 'content' => 'Sto salvando il tuo ticket...']],
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
                            'phone' => ['type' => 'string', 'description' => 'Numero di telefono del chiamante'],
                            'description' => ['type' => 'string', 'description' => 'Descrizione dettagliata del problema']
                        ],
                        'required' => ['assistance_type', 'company_name', 'customer_name', 'description']
                    ]
                ],
                'server' => ['url' => $webhookUrl]
            ];

            $saveTicketToolId = $this->upsertTool($saveTicketToolId, $saveTicketConfig);

            // 3. Update Assistant
            $basePrompt = $finalPrompt;
            $instr = "ISTRUZIONE OBBLIGATORIA: Prima di salvare un ticket con 'save_ticket', devi SEMPRE chiamare 'get_assistance_types' per conoscere i reparti disponibili.\n" .
                     "IMPORTANTE: Usa SOLO i reparti restituiti dalla funzione 'get_assistance_types'. Non inventare reparti.\n" .
                     "Chiedi all'utente a quale di questi reparti desidera rivolgersi.\n\n";
            
            if (strpos($basePrompt, 'get_assistance_types') === false) {
                $basePrompt = $instr . $basePrompt;
            }

            $modelConfig['messages'] = [['role' => 'system', 'content' => $basePrompt]];
            // Ensure tools are linked (Vapi uses toolIds in model config)
            $modelConfig['toolIds'] = array_filter([$getAssistanceToolId, $saveTicketToolId]);
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
                Log::error('VapiService: Error updating assistant: ' . $response->body());
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
            return $res->successful() ? $res->json()['id'] : null;
        } else {
            Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->patch("{$this->baseUrl}/tool/{$toolId}", $config);
            return $toolId;
        }
    }
}
