<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\VapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VapiController extends Controller
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
     * Display the current prompt from local DB or Vapi.ai fallback
     */
    public function index()
    {
        // 1. Prova a leggere dal DB locale
        $prompt = Setting::where('key', 'vapi_prompt')->value('value');
        $welcome_message = Setting::where('key', 'vapi_welcome_message')->value('value');

        // 2. Se mancano, recupera da Vapi e inizializza il DB
        if (!$prompt || !$welcome_message) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/assistant/{$this->assistantId}");

            if ($response->successful()) {
                $assistantData = $response->json();
                $prompt = $prompt ?: ($assistantData['model']['messages'][0]['content'] ?? '');
                $welcome_message = $welcome_message ?: ($assistantData['firstMessage'] ?? '');
                
                // Salva nel DB per la prossima volta
                if ($prompt) Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $prompt]);
                if ($welcome_message) Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $welcome_message]);
            }
        }

        // Recupera info assistente per UI
        $assistantRes = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])->get("{$this->baseUrl}/assistant/{$this->assistantId}");
        $assistant = $assistantRes->successful() ? $assistantRes->json() : [];

        return view('admin.vapi.index', compact('prompt', 'welcome_message', 'assistant'));
    }

    /**
     * Update the prompt and welcome message locally and on Vapi.ai
     */
    public function update(Request $request, VapiService $vapiService)
    {
        $request->validate([
            'prompt' => 'required|string',
            'welcome_message' => 'required|string',
        ]);

        $prompt = $request->input('prompt');
        $welcome = $request->input('welcome_message');

        // 1. Salva nel database locale (Origine della Verità)
        Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $prompt]);
        Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $welcome]);

        // 2. Sincronizza con Vapi.ai
        $success = $vapiService->syncAssistantConfig($prompt, $welcome);

        if (!$success) {
            return back()->withErrors(['api' => 'Errore nella sincronizzazione con Vapi.ai. Il DB locale è stato comunque aggiornato.']);
        }

        return redirect()->route('admin.vapi.index')->with('success', 'Configurazione salvata nel database e sincronizzata con Vapi.ai.');
    }
}
