<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agente AI - Configurazione Prompt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Assistente: {{ $assistant['name'] ?? 'Riley' }}</h3>
                            <a href="{{ route('admin.vapi.tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Visualizza Ticket Ricevuti
                            </a>
                        </div>
                        <p class="text-sm text-gray-600">ID: <code class="bg-gray-100 px-1 rounded">{{ $assistant['id'] ?? '' }}</code></p>
                        <div class="mt-4 p-4 bg-indigo-50 border border-indigo-100 rounded-md">
                            <h4 class="text-sm font-bold text-indigo-800 mb-1">Istruzioni per il Sistema Ticket</h4>
                            <p class="text-xs text-indigo-700">Il sistema è pronto a ricevere ticket. Assicurati che il prompt termini chiedendo all'AI di chiamare la funzione <code>save_ticket</code> con i parametri: <code>assistance_type</code>, <code>company_name</code>, <code>customer_name</code>, e <code>description</code>.</p>
                            
                            <div class="mt-3 pt-3 border-t border-indigo-200">
                                <h5 class="text-xs font-bold text-indigo-900 uppercase">URL Webhook per Vapi:</h5>
                                <div class="flex items-center gap-2 mt-1">
                                    <code class="bg-white px-2 py-1 rounded border border-indigo-200 text-[10px] flex-1 text-indigo-600 truncate">{{ url('/api/vapi/webhook') }}</code>
                                    <button onclick="navigator.clipboard.writeText('{{ url('/api/vapi/webhook') }}'); alert('URL Copiato!')" class="text-[10px] bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">Copia</button>
                                </div>
                                <p class="text-[10px] text-indigo-500 mt-1 italic">Usa questo URL nella sezione "Server URL" di Vapi se l'aggiornamento automatico non dovesse funzionare.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.vapi.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2 font-bold uppercase tracking-wider">Prompt (System Message)</label>
                            <textarea 
                                name="prompt" 
                                id="prompt" 
                                rows="10" 
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm mb-4"
                                placeholder="Inserisci qui le istruzioni per l'agente AI..."
                            >{{ old('prompt', $prompt) }}</textarea>

                            <label for="welcome_message" class="block text-sm font-medium text-gray-700 mb-2 font-bold uppercase tracking-wider">Messaggio di Benvenuto (First Message)</label>
                            <textarea 
                                name="welcome_message" 
                                id="welcome_message" 
                                rows="3" 
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm"
                                placeholder="Inserisci qui il saluto iniziale dell'assistente..."
                            >{{ old('welcome_message', $welcome_message) }}</textarea>
                            
                            <p class="mt-4 text-sm text-gray-500">
                                <strong>Prompt</strong>: definisce il comportamento e la personalità dell'agente.<br>
                                <strong>Messaggio di Benvenuto</strong>: è la prima frase pronunciata dall'agente all'inizio della chiamata.
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Salva Modifiche su Vapi.ai
                            </button>
                            <a href="https://dashboard.vapi.ai" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Vai alla Dashboard di Vapi.ai &rarr;
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
