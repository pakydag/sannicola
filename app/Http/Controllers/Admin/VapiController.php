<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VapiFile;
use App\Services\VapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        // 1. Configurazione Base
        $prompt = Setting::where('key', 'vapi_prompt')->value('value');
        $welcome_message = Setting::where('key', 'vapi_welcome_message')->value('value');
        $language = Setting::where('key', 'vapi_language')->value('value') ?: 'it-IT';
        
        // 2. Parametri Voce
        $voice_stability = Setting::where('key', 'vapi_voice_stability')->value('value') ?: 0.5;
        $voice_similarity = Setting::where('key', 'vapi_voice_similarity')->value('value') ?: 0.75;
        $voice_speed = Setting::where('key', 'vapi_voice_speed')->value('value') ?: 1.0;

        // 3. Additional Voice Configuration
        $voice_background_sound = Setting::where('key', 'vapi_voice_background_sound')->value('value') ?: 'off';
        $voice_background_sound_url = Setting::where('key', 'vapi_voice_background_sound_url')->value('value');
        $voice_input_min_characters = Setting::where('key', 'vapi_voice_input_min_characters')->value('value') ?: 30;

        // 4. Files Knowledge Base
        $files = VapiFile::all();

        // 4. Inizializzazione se mancano dati critici
        if (!$prompt || !$welcome_message) {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->baseUrl}/assistant/{$this->assistantId}");

            if ($response->successful()) {
                $assistantData = $response->json();
                $prompt = $prompt ?: ($assistantData['model']['messages'][0]['content'] ?? '');
                $welcome_message = $welcome_message ?: ($assistantData['firstMessage'] ?? '');
                
                if ($prompt) Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $prompt]);
                if ($welcome_message) Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $welcome_message]);
            }
        }

        try {
            $assistantRes = Http::timeout(10)->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->baseUrl}/assistant/{$this->assistantId}");
            $assistant = $assistantRes->successful() ? $assistantRes->json() : [];

            // 4. Recupera Voci Disponibili da Vapi
            $voicesRes = Http::timeout(10)->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->baseUrl}/voice");
            $availableVoices = $voicesRes->successful() ? $voicesRes->json() : [];
        } catch (\Exception $e) {
            Log::warning('VapiController: Connection timeout or error: ' . $e->getMessage());
            $assistant = [];
            $availableVoices = [];
        }
        
        $voice_id = Setting::where('key', 'vapi_voice_id')->value('value') ?: ($assistant['voice']['voiceId'] ?? '');

        return view('admin.vapi.index', compact(
            'prompt', 
            'welcome_message', 
            'language',
            'voice_stability',
            'voice_similarity',
            'voice_speed',
            'files',
            'assistant',
            'availableVoices',
            'voice_id',
            'voice_background_sound',
            'voice_background_sound_url',
            'voice_input_min_characters'
        ));
    }

    public function update(Request $request, VapiService $vapiService)
    {
        $request->validate([
            'prompt' => 'required|string',
            'welcome_message' => 'required|string',
            'language' => 'required|string|in:it-IT,en-US',
            'voice_stability' => 'required|numeric|min:0|max:1',
            'voice_similarity' => 'required|numeric|min:0|max:1',
            'voice_speed' => 'required|numeric|min:0.5|max:2',
            'voice_id' => 'required|string',
            'voice_background_sound' => 'required|string|in:off,office,hospital,custom',
            'voice_background_sound_url' => 'nullable|url',
            'voice_input_min_characters' => 'required|integer|min:1|max:500',
        ]);

        // Salva tutto nel database locale
        Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $request->input('prompt')]);
        Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $request->input('welcome_message')]);
        Setting::updateOrCreate(['key' => 'vapi_language'], ['value' => $request->input('language')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_stability'], ['value' => $request->input('voice_stability')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_similarity'], ['value' => $request->input('voice_similarity')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_speed'], ['value' => $request->input('voice_speed')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_id'], ['value' => $request->input('voice_id')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_background_sound'], ['value' => $request->input('voice_background_sound')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_background_sound_url'], ['value' => $request->input('voice_background_sound_url')]);
        Setting::updateOrCreate(['key' => 'vapi_voice_input_min_characters'], ['value' => $request->input('voice_input_min_characters')]);

        // Sincronizza con Vapi.ai
        $success = $vapiService->syncAssistantConfig();

        if (!$success) {
            return back()->withErrors(['api' => 'Errore nella sincronizzazione con Vapi.ai. Il DB locale è stato comunque aggiornato.']);
        }

        return redirect()->route('admin.vapi.index')->with('success', 'Configurazione salvata e sincronizzata con Vapi.ai.');
    }

    /**
     * Upload a PDF file to Vapi Knowledge Base
     */
    public function uploadFile(Request $request, VapiService $vapiService)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $file = $request->file('pdf_file');
        $path = $file->store('vapi_knowledge', 'public');

        $vapiFileId = $vapiService->uploadFileToVapi(storage_path('app/public/' . $path), $file->getClientOriginalName());

        if (!$vapiFileId) {
            Storage::disk('public')->delete($path);
            return back()->withErrors(['file' => 'Errore nel caricamento del file su Vapi.ai.']);
        }

        VapiFile::create([
            'name' => $file->getClientOriginalName(),
            'vapi_file_id' => $vapiFileId,
            'path' => $path,
            'size' => $file->getSize(),
        ]);

        // Forza rinfresco assistente per includere il nuovo file
        $vapiService->syncAssistantConfig();

        return back()->with('success', 'File caricato e aggiunto alla Knowledge Base di Vanessa.');
    }

    /**
     * Delete a file from local DB and Vapi.ai
     */
    public function deleteFile($id, VapiService $vapiService)
    {
        $file = VapiFile::findOrFail($id);
        
        // Rimuovi da Vapi (opzionale, ma pulito)
        if ($file->vapi_file_id) {
            $vapiService->deleteFileFromVapi($file->vapi_file_id);
        }

        // Rimuovi locale
        Storage::disk('public')->delete($file->path);
        $file->delete();

        // Rinfresca assistente
        $vapiService->syncAssistantConfig();

        return back()->with('success', 'File rimosso correttamente.');
    }
}
