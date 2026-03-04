<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VapiController extends Controller
{
    private $assistantId = '5a05fa0e-87e8-43cc-969e-4c5c469670de';
    private $apiKey     = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
    private $baseUrl    = 'https://api.vapi.ai';

    /**
     * Display the current prompt from Vapi.ai
     */
    public function index()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUrl}/assistant/{$this->assistantId}");

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Errore nella comunicazione con Vapi.ai: ' . $response->body()]);
        }

        $assistant = $response->json();
        $prompt = $assistant['model']['messages'][0]['content'] ?? '';
        $welcome_message = $assistant['firstMessage'] ?? '';

        return view('admin.vapi.index', compact('prompt', 'welcome_message', 'assistant'));
    }

    /**
     * Update the prompt and welcome message on Vapi.ai
     */
    public function update(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'welcome_message' => 'required|string',
        ]);

        // Fetch current assistant to preserve required model fields
        $getCurrent = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUrl}/assistant/{$this->assistantId}");

        if ($getCurrent->failed()) {
            return back()->withErrors(['api' => 'Impossibile recuperare i dati attuali da Vapi.ai: ' . $getCurrent->body()]);
        }

        $assistant = $getCurrent->json();
        $modelConfig = $assistant['model'] ?? [];
        
        // 1. Assicurati che il tool "save_ticket" esista come risorsa standalone
        $toolsListResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUrl}/tool");

        $toolId = null;
        if ($toolsListResponse->successful()) {
            foreach ($toolsListResponse->json() as $tool) {
                if (isset($tool['function']['name']) && $tool['function']['name'] === 'save_ticket') {
                    $toolId = $tool['id'];
                    break;
                }
            }
        }

        $toolConfig = [
            'type' => 'function',
            'messages' => [
                [
                    'type' => 'request-start',
                    'content' => 'Sto salvando il tuo ticket...',
                ],
            ],
            'function' => [
                'name' => 'save_ticket',
                'description' => 'Salva un ticket di assistenza nel database locale.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'assistance_type' => [
                            'type' => 'string',
                            'description' => 'Il tipo di assistenza scelta (Cedam, Web, Applicativi)',
                            'enum' => ['Software Cedam', 'Software Web', 'Applicativi Web']
                        ],
                        'company_name' => [
                            'type' => 'string',
                            'description' => 'Il nome dell\'azienda che chiama'
                        ],
                        'customer_name' => [
                            'type' => 'string',
                            'description' => 'Nome e Cognome della persona'
                        ],
                        'description' => [
                            'type' => 'string',
                            'description' => 'Descrizione dettagliata del problema'
                        ]
                    ],
                    'required' => ['assistance_type', 'company_name', 'customer_name', 'description']
                ]
            ],
            'server' => [
                'url' => url('/api/vapi/webhook')
            ]
        ];

        if (!$toolId) {
            // Crea il tool se non esiste
            $createToolResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post("{$this->baseUrl}/tool", $toolConfig);

            if ($createToolResponse->successful()) {
                $toolId = $createToolResponse->json()['id'];
            } else {
                return back()->withErrors(['api' => 'Errore nella creazione del tool: ' . $createToolResponse->body()]);
            }
        } else {
            // Aggiorna l'URL del tool esistente (per ngrok)
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->patch("{$this->baseUrl}/tool/{$toolId}", [
                'server' => ['url' => url('/api/vapi/webhook')]
            ]);
        }

        // 2. Collega il tool all'assistente tramite toolIds
        $toolIds = $modelConfig['toolIds'] ?? [];
        if (!in_array($toolId, $toolIds)) {
            $toolIds[] = $toolId;
        }
        // 3. Update model config: REMOVE inline tools to avoid conflict, use only toolIds
        unset($modelConfig['tools']); 
        
        $modelConfig['toolIds'] = $toolIds;

        // Update messages with the new prompt
        $modelConfig['messages'] = [
            [
                'role' => 'system',
                'content' => $request->input('prompt')
            ]
        ];

        $payload = [
            'model' => $modelConfig,
            'firstMessage' => $request->input('welcome_message'),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->patch("{$this->baseUrl}/assistant/{$this->assistantId}", $payload);

        if ($response->failed()) {
            return back()->withErrors(['api' => 'Errore nell\'aggiornamento dell\'assistente: ' . $response->body()]);
        }

        return redirect()->route('admin.vapi.index')->with('success', 'Configurazione aggiornata con successo su Vapi.ai.');
    }
}
