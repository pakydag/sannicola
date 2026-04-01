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
    public function update(Request $request, \App\Services\VapiService $vapiService)
    {
        $request->validate([
            'prompt' => 'required|string',
            'welcome_message' => 'required|string',
        ]);

        $success = $vapiService->syncAssistantConfig(
            $request->input('prompt'), 
            $request->input('welcome_message')
        );

        if (!$success) {
            return back()->withErrors(['api' => 'Errore nell\'aggiornamento dell\'assistente su Vapi.ai. Controlla i log per i dettagli.']);
        }

        return redirect()->route('admin.vapi.index')->with('success', 'Configurazione aggiornata con successo su Vapi.ai.');
    }
}
