<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pannello Configurazione Assistente AI') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.vapi.tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Ticket Ricevuti
                </a>
                <a href="https://dashboard.vapi.ai" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Sito Vapi.ai &rarr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 shadow-sm rounded-r-md" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 shadow-sm rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.vapi.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Colonna Sinistra: Identità -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 uppercase">Identità e Istruzioni</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="prompt" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">Prompt di Sistema (System Message)</label>
                                    <textarea name="prompt" id="prompt" rows="12" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm font-mono text-sm" placeholder="Definisci qui il comportamento dell'assistente...">{{ old('prompt', $prompt) }}</textarea>
                                </div>

                                <div>
                                    <label for="welcome_message" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">Messaggio di Benvenuto (First Message)</label>
                                    <input type="text" name="welcome_message" id="welcome_message" value="{{ old('welcome_message', $welcome_message) }}" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm" placeholder="Come posso aiutarti?">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonna Destra: Parametri Tecnici -->
                    <div class="space-y-6">
                        <!-- Lingua e Voce -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-purple-100 rounded-lg text-purple-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 uppercase">Voce e Lingua</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="language" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">Lingua Assistente</label>
                                    <select name="language" id="language" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm">
                                        <option value="it-IT" {{ $language == 'it-IT' ? 'selected' : '' }}>Italiano (it-IT)</option>
                                        <option value="en-US" {{ $language == 'en-US' ? 'selected' : '' }}>English (en-US)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="voice_id" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">Scegli Voce</label>
                                    <select name="voice_id" id="voice_id" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm">
                                        @if(empty($availableVoices))
                                            <option value="{{ $voice_id }}">Voce Corrente: {{ $voice_id }} (Errore caricamento lista)</option>
                                        @else
                                            @foreach($availableVoices as $voice)
                                                <option value="{{ $voice['id'] }}" {{ $voice_id == $voice['id'] ? 'selected' : '' }}>
                                                    {{ $voice['name'] ?? 'Senza Nome' }} ({{ ucfirst($voice['provider'] ?? 'Sconosciuto') }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if(empty($availableVoices))
                                        <p class="mt-1 text-[10px] text-red-500 italic text-right">Impossibile recuperare l'elenco completo da Vapi.ai in questo momento.</p>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Stabilità Voce</label>
                                        <span class="text-xs font-bold text-indigo-600" id="stability-val">{{ $voice_stability }}</span>
                                    </div>
                                    <input type="range" name="voice_stability" min="0" max="1" step="0.05" value="{{ $voice_stability }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="document.getElementById('stability-val').innerText = this.value">
                                </div>

                                <div>
                                    <div class="flex justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Chiarezza / Somiglianza</label>
                                        <span class="text-xs font-bold text-indigo-600" id="similarity-val">{{ $voice_similarity }}</span>
                                    </div>
                                    <input type="range" name="voice_similarity" min="0" max="1" step="0.05" value="{{ $voice_similarity }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="document.getElementById('similarity-val').innerText = this.value">
                                </div>

                                <div>
                                    <div class="flex justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Velocità di Eloquio</label>
                                        <span class="text-xs font-bold text-indigo-600" id="speed-val">{{ $voice_speed }}</span>
                                    </div>
                                    <input type="range" name="voice_speed" min="0.5" max="2" step="0.1" value="{{ $voice_speed }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="document.getElementById('speed-val').innerText = this.value">
                                </div>

                                <div class="pt-6 border-t border-gray-100 space-y-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Configurazioni Avanzate</h4>
                                    
                                    <div>
                                        <label for="voice_background_sound" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">Suono di Sfondo</label>
                                        <select name="voice_background_sound" id="voice_background_sound" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm" onchange="document.getElementById('bg-sound-url-container').style.display = (this.value === 'custom' ? 'block' : 'none')">
                                            <option value="off" {{ $voice_background_sound == 'off' ? 'selected' : '' }}>Nessuno (Off)</option>
                                            <option value="office" {{ $voice_background_sound == 'office' ? 'selected' : '' }}>Ufficio (Office)</option>
                                            <option value="hospital" {{ $voice_background_sound == 'hospital' ? 'selected' : '' }}>Ospedale (Hospital)</option>
                                            <option value="custom" {{ $voice_background_sound == 'custom' ? 'selected' : '' }}>Personalizzato (Custom)</option>
                                        </select>
                                    </div>

                                    <div id="bg-sound-url-container" style="display: {{ $voice_background_sound == 'custom' ? 'block' : 'none' }}">
                                        <label for="voice_background_sound_url" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-widest">URL Suono Personalizzato</label>
                                        <input type="url" name="voice_background_sound_url" id="voice_background_sound_url" value="{{ old('voice_background_sound_url', $voice_background_sound_url) }}" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm" placeholder="https://esempio.it/suono.mp3">
                                    </div>

                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <label for="voice_input_min_characters" class="text-xs font-bold text-gray-500 uppercase tracking-widest">Caratteri Minimi Input</label>
                                            <span class="text-xs font-bold text-indigo-600 tooltip" title="Determina quanto l'AI aspetta prima di rispondere. Valore consigliato: 30.">?</span>
                                        </div>
                                        <input type="number" name="voice_input_min_characters" id="voice_input_min_characters" value="{{ old('voice_input_min_characters', $voice_input_min_characters) }}" min="1" max="500" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm">
                                        <p class="text-[10px] text-gray-400 mt-1 italic">Determina la sensibilità al parlato prima di generare la risposta.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition duration-150">
                            Salva e Sincronizza
                        </button>
                    </div>
                </div>
            </form>

            <!-- Sezione Knowledge Base -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mt-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 uppercase">Knowledge Base (PDF)</h3>
                    </div>
                    
                    <form action="{{ route('admin.vapi.files.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                        @csrf
                        <input type="file" name="pdf_file" accept=".pdf" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition">Carica PDF</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="pb-3 px-2">Nome Documento</th>
                                <th class="pb-3 px-2 text-center">Vapi ID</th>
                                <th class="pb-3 px-2 text-center">Dimensione</th>
                                <th class="pb-3 px-2 text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse($files as $file)
                                <tr>
                                    <td class="py-4 px-2 font-medium text-gray-900">{{ $file->name }}</td>
                                    <td class="py-4 px-2 text-center"><code class="text-[10px] bg-gray-50 px-1 rounded">{{ $file->vapi_file_id }}</code></td>
                                    <td class="py-4 px-2 text-center text-gray-500 uppercase">{{ number_format($file->size / 1024, 1) }} KB</td>
                                    <td class="py-4 px-2 text-right">
                                        <form action="{{ route('admin.vapi.files.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Sicuro di voler rimuovere questo documento dalla Knowledge Base?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-widest">Rimuovi</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 italic">Nessun documento presente nella Knowledge Base.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
