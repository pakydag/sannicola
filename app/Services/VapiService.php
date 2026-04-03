<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Setting;
use App\Models\VapiFile;
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
            // 1. Recupera dati attuali e impostazioni locali
            $getCurrent = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->baseUrl}/assistant/{$this->assistantId}");

            if ($getCurrent->failed()) return false;
            $assistant = $getCurrent->json();
            
            $finalPrompt = $prompt ?: Setting::where('key', 'vapi_prompt')->value('value');
            $finalWelcome = $welcomeMessage ?: Setting::where('key', 'vapi_welcome_message')->value('value');
            $language = Setting::where('key', 'vapi_language')->value('value') ?: 'it-IT';
            
            $stability = (float)(Setting::where('key', 'vapi_voice_stability')->value('value') ?: 0.5);
            $similarity = (float)(Setting::where('key', 'vapi_voice_similarity')->value('value') ?: 0.75);
            $speed = (float)(Setting::where('key', 'vapi_voice_speed')->value('value') ?: 1.0);
            $voiceId = Setting::where('key', 'vapi_voice_id')->value('value') ?: ($assistant['voice']['voiceId'] ?? '');
            
            $bgSound = Setting::where('key', 'vapi_voice_background_sound')->value('value') ?: 'off';
            $bgSoundUrl = Setting::where('key', 'vapi_voice_background_sound_url')->value('value');
            $inputMinChars = (int)(Setting::where('key', 'vapi_voice_input_min_characters')->value('value') ?: 30);

            $fileIds = VapiFile::whereNotNull('vapi_file_id')->pluck('vapi_file_id')->toArray();

            // 2. Sync Tools (Immutata logica dei tool)
            $toolsListResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->get("{$this->baseUrl}/tool");
            $allTools = $toolsListResponse->successful() ? $toolsListResponse->json() : [];

            $getAssistanceToolId = collect($allTools)->firstWhere('function.name', 'get_assistance_types')['id'] ?? null;
            $saveTicketToolId = collect($allTools)->firstWhere('function.name', 'save_ticket')['id'] ?? null;
            $checkAvailabilityToolId = collect($allTools)->firstWhere('function.name', 'check_availability')['id'] ?? null;
            $bookAppointmentToolId = collect($allTools)->firstWhere('function.name', 'book_appointment')['id'] ?? null;
            $getCustomerContextToolId = collect($allTools)->firstWhere('function.name', 'get_customer_context')['id'] ?? null;
            $updateContactInfoToolId = collect($allTools)->firstWhere('function.name', 'update_contact_info')['id'] ?? null;
            $transferCallToolId = collect($allTools)->firstWhere('function.name', 'transfer_call')['id'] ?? null;
            $cancelAppointmentToolId = collect($allTools)->firstWhere('function.name', 'cancel_appointment')['id'] ?? null;

            $departments = Department::where('is_active', true)->pluck('name')->toArray() ?: ['Generico'];
            $webhookUrl = $this->webhookUrl;

            // Inizializza i Tool (Upsert)
            
            // Tool di trasferimento chiamata
            $transferCallToolId = $this->upsertTool($transferCallToolId, [
                'type' => 'transferCall',
                'destinations' => [
                    [
                        'type' => 'number',
                        'number' => '+393290952802',
                        'message' => 'Ti sto trasferendo un cliente, resta in linea.'
                    ]
                ],
                'function' => [
                    'name' => 'transfer_call',
                    'description' => 'Trasferisce la chiamata all\'amministratore (Pasquale).'
                ]
            ]);
            $getAssistanceToolId = $this->upsertTool($getAssistanceToolId, ['type' => 'function', 'function' => ['name' => 'get_assistance_types', 'description' => 'Ottiene reparti.', 'parameters' => ['type' => 'object', 'properties' => (object)[]]], 'server' => ['url' => $webhookUrl]]);
            $saveTicketToolId = $this->upsertTool($saveTicketToolId, ['type' => 'function', 'function' => ['name' => 'save_ticket', 'description' => 'Salva ticket.', 'parameters' => ['type' => 'object', 'properties' => ['assistance_type' => ['type' => 'string', 'enum' => $departments], 'company_name' => ['type' => 'string'], 'customer_name' => ['type' => 'string'], 'email' => ['type' => 'string'], 'phone' => ['type' => 'string'], 'description' => ['type' => 'string']], 'required' => ['assistance_type', 'company_name', 'customer_name', 'phone', 'description']]], 'server' => ['url' => $webhookUrl]]);
            $checkAvailabilityToolId = $this->upsertTool($checkAvailabilityToolId, ['type' => 'function', 'function' => ['name' => 'check_availability', 'description' => 'Verifica disponibilità.', 'parameters' => ['type' => 'object', 'properties' => ['department_name' => ['type' => 'string', 'enum' => $departments], 'date' => ['type' => 'string']], 'required' => ['department_name', 'date']]], 'server' => ['url' => $webhookUrl]]);
            $bookAppointmentToolId = $this->upsertTool($bookAppointmentToolId, ['type' => 'function', 'function' => ['name' => 'book_appointment', 'description' => 'Prenota appuntamento.', 'parameters' => ['type' => 'object', 'properties' => ['department_name' => ['type' => 'string', 'enum' => $departments], 'customer_name' => ['type' => 'string'], 'phone' => ['type' => 'string'], 'date' => ['type' => 'string'], 'time' => ['type' => 'string'], 'reason' => ['type' => 'string']], 'required' => ['department_name', 'customer_name', 'phone', 'date', 'time', 'reason']]], 'server' => ['url' => $webhookUrl]]);
            $getCustomerContextToolId = $this->upsertTool($getCustomerContextToolId, [
                'type' => 'function', 
                'async' => false,
                'messages' => [
                    ['type' => 'request-start', 'content' => '']
                ],
                'function' => [
                    'name' => 'get_customer_context', 
                    'description' => 'Ottiene contesto cliente (ticket e appuntamenti). NON CHIEDERE IL NUMERO, il sistema lo rileva in automatico.', 
                    'parameters' => [
                        'type' => 'object', 
                        'properties' => ['phone' => ['type' => 'string']]
                    ]
                ], 
                'server' => ['url' => $webhookUrl]
            ]);

            $updateContactInfoToolId = $this->upsertTool($updateContactInfoToolId, [
                'type' => 'function', 
                'function' => [
                    'name' => 'update_contact_info', 
                    'description' => 'Aggiorna i dati anagrafici del contatto (nome, email, azienda, indirizzo, città). Usa questo tool se l\'utente ti comunica nuovi dati.', 
                    'parameters' => [
                        'type' => 'object', 
                        'properties' => [
                            'first_name' => ['type' => 'string'],
                            'last_name' => ['type' => 'string'],
                            'email' => ['type' => 'string'],
                            'company_name' => ['type' => 'string'],
                            'address' => ['type' => 'string'],
                            'city' => ['type' => 'string'],
                            'phone' => ['type' => 'string', 'description' => 'Opzionale, se cambia numero di telefono']
                        ]
                    ]
                ], 
                'server' => ['url' => $webhookUrl]
            ]);

            $cancelAppointmentToolId = $this->upsertTool($cancelAppointmentToolId, [
                'type' => 'function', 
                'function' => [
                    'name' => 'cancel_appointment', 
                    'description' => 'Annulla l\'ultimo appuntamento futuro confermato del cliente.', 
                    'parameters' => ['type' => 'object', 'properties' => (object)[]]
                ], 
                'server' => ['url' => $webhookUrl]
            ]);

            $modelConfig = $assistant['model'];
            $modelConfig['messages'] = [['role' => 'system', 'content' => $this->preparePrompt($finalPrompt)]];
            $modelConfig['toolIds'] = array_filter([
                $getAssistanceToolId, 
                $saveTicketToolId, 
                $checkAvailabilityToolId, 
                $bookAppointmentToolId, 
                $getCustomerContextToolId, 
                $updateContactInfoToolId, 
                $cancelAppointmentToolId,
                $transferCallToolId
            ]);

            if (!empty($fileIds)) {
                $modelConfig['knowledgeBase'] = ['provider' => 'vapi', 'fileIds' => $fileIds];
            } else {
                unset($modelConfig['knowledgeBase']);
            }

            $voiceConfig = $assistant['voice'];
            $voiceConfig['voiceId'] = $voiceId;
            $voiceConfig['stability'] = $stability;
            $voiceConfig['similarityBoost'] = $similarity;
            $voiceConfig['speed'] = $speed;
            $payload = [
                'model' => $modelConfig,
                'voice' => $voiceConfig,
                'firstMessage' => null, 
                'firstMessageMode' => 'assistant-speaks-first-with-model-generated-message', 
                'server' => [
                    'url' => $webhookUrl,
                ],
            ];

            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->patch("{$this->baseUrl}/assistant/{$this->assistantId}", $payload);

            if ($response->failed()) {
                Log::error('Vapi Sync Failed: ' . $response->status() . ' - ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('VapiService Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function preparePrompt($basePrompt)
    {
        $now = now()->setTimezone('Europe/Rome');
        $dateStr = "DATA ATTUALE: " . $now->format('l, d F Y, H:i');
        if (strpos($basePrompt, 'DATA ATTUALE:') !== false) {
            $basePrompt = preg_replace('/DATA ATTUALE: .*/', $dateStr, $basePrompt);
        } else {
            $basePrompt = $dateStr . "\n" . $basePrompt;
        }
        return $basePrompt;
    }

    /**
     * Upload a file to Vapi and get the ID
     */
    public function uploadFileToVapi($filePath, $fileName)
    {
        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->attach('file', file_get_contents($filePath), $fileName)
                ->post("{$this->baseUrl}/file");

            return $response->successful() ? ($response->json()['id'] ?? null) : null;
        } catch (\Exception $e) {
            Log::error('VapiService File Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a file from Vapi
     */
    public function deleteFileFromVapi($fileId)
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
            ->delete("{$this->baseUrl}/file/{$fileId}")->successful();
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
            unset($config['type'], $config['id'], $config['orgId'], $config['createdAt'], $config['updatedAt']);
            Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->patch("{$this->baseUrl}/tool/{$toolId}", $config);
            return $toolId;
        }
    }

    /**
     * Get details for a specific call from Vapi
     */
    public function getCallDetails($callId)
    {
        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->baseUrl}/call/{$callId}");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('VapiService Get Call Details Error: ' . $e->getMessage());
            return null;
        }
    }
}
