<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VapiController extends Controller
{
    private $assistantId;
    private $apiKey;
    private $baseUrl    = 'https://api.vapi.ai';

    public function __construct()
    {
        $this->assistantId = \App\Models\Setting::where('key', 'vapi_assistant_id')->value('value') ?: '5a05fa0e-87e8-43cc-969e-4c5c469670de';
        $this->apiKey = \App\Models\Setting::where('key', 'vapi_key')->value('value') ?: '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
    }

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
        
        // 1. Sincronizzazione Tool "get_assistance_types"
        $toolsListResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->get("{$this->baseUrl}/tool");
        $allTools = $toolsListResponse->successful() ? $toolsListResponse->json() : [];

        $getAssistanceToolId = null;
        $saveTicketToolId = null;

        foreach ($allTools as $tool) {
            $name = $tool['function']['name'] ?? '';
            if ($name === 'get_assistance_types') $getAssistanceToolId = $tool['id'];
            if ($name === 'save_ticket') $saveTicketToolId = $tool['id'];
        }

        // Configurazione Tool: get_assistance_types
        $getAssistanceConfig = [
            'type' => 'function',
            'function' => [
                'name' => 'get_assistance_types',
                'description' => 'Ottiene l\'elenco dei reparti di assistenza disponibili nel sistema (es. Software, Web, Amministrazione) per smistare correttamente il ticket.',
                'parameters' => ['type' => 'object', 'properties' => (object)[]]
            ],
            'server' => ['url' => request()->root() . '/api/vapi/webhook']
        ];

        if (!$getAssistanceToolId) {
            $res = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->post("{$this->baseUrl}/tool", $getAssistanceConfig);
            if ($res->successful()) $getAssistanceToolId = $res->json()['id'];
        } else {
            Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->patch("{$this->baseUrl}/tool/{$getAssistanceToolId}", $getAssistanceConfig);
        }

        // Configurazione Tool: save_ticket (Dinamico)
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
                            'description' => 'Il nome esatto del reparto scelto tra quelli disponibili (ottenuti da get_assistance_types).'
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
            'server' => ['url' => request()->root() . '/api/vapi/webhook']
        ];

        if (!$saveTicketToolId) {
            $res = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->post("{$this->baseUrl}/tool", $saveTicketConfig);
            if ($res->successful()) $saveTicketToolId = $res->json()['id'];
        } else {
            Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->patch("{$this->baseUrl}/tool/{$saveTicketToolId}", $saveTicketConfig);
        }

        // 2. Collega i tool all'assistente
        $toolIds = array_filter([$getAssistanceToolId, $saveTicketToolId]);
        $modelConfig['toolIds'] = $toolIds;
        unset($modelConfig['tools']); 

        // 3. Aggiorna Prompt con istruzioni di sequenza
        $basePrompt = $request->input('prompt');
        $instr = "ISTRUZIONE OBBLIGATORIA: Prima di salvare un ticket con 'save_ticket', devi SEMPRE chiamare 'get_assistance_types' per conoscere i reparti disponibili.\n" .
                 "IMPORTANTE: Usa SOLO i reparti restituiti dalla funzione 'get_assistance_types'. Non inventare reparti e non usare opzioni predefinite se non coincidono con quella ottenute dal sistema.\n" .
                 "Chiedi all'utente a quale di questi reparti desidera rivolgersi.\n\n";
        
        if (strpos($basePrompt, 'get_assistance_types') === false) {
            $basePrompt = $instr . $basePrompt;
        }

        $modelConfig['messages'] = [['role' => 'system', 'content' => $basePrompt]];

        $payload = [
            'model' => $modelConfig,
            'firstMessage' => $request->input('welcome_message'),
            'serverUrl' => request()->root() . '/api/vapi/webhook',
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
