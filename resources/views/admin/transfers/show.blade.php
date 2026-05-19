<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Conversazione Richiesta Transfer da {{ $request->nome }} {{ $request->cognome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Top Bar -->
                    <div class="mb-6 flex justify-between items-center border-b pb-4">
                        <div>
                            <span class="text-gray-500 text-sm">Ricevuta il: {{ $request->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <a href="{{ route('admin.transfers.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                            &larr; Torna all'elenco
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Dettagli Mittente & Transfer Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-2">Dati Mittente</h3>
                            <ul class="text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm">
                                <li class="mb-2"><strong class="font-semibold w-32 inline-block">Nome e Cognome:</strong> {{ $request->nome }} {{ $request->cognome }}</li>
                                <li class="mb-2">
                                    <strong class="font-semibold w-32 inline-block">Email:</strong> 
                                    <a href="mailto:{{ $request->email }}" class="text-indigo-600 hover:underline">{{ $request->email }}</a>
                                </li>
                                @if($request->telefono)
                                    <li>
                                        <strong class="font-semibold w-32 inline-block">Telefono:</strong> 
                                        <a href="tel:{{ $request->telefono }}" class="text-indigo-600 hover:underline">{{ $request->telefono }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-2">Dettagli Transfer</h3>
                            <ul class="text-gray-700 bg-indigo-50/50 p-4 rounded-lg border border-indigo-200 text-sm">
                                <li class="mb-1"><strong class="font-semibold w-32 inline-block">Luogo di Arrivo:</strong> {{ $request->luogo_arrivo }}</li>
                                <li class="mb-1"><strong class="font-semibold w-32 inline-block">Data e Orario:</strong> {{ \Carbon\Carbon::parse($request->data)->format('d/m/Y') }} alle {{ $request->orario }}</li>
                                <li class="mb-1"><strong class="font-semibold w-32 inline-block">Persone:</strong> {{ $request->numero_persone }}</li>
                                <li class="mb-1"><strong class="font-semibold w-32 inline-block">Andata e Ritorno:</strong> {{ $request->andata_ritorno ? 'Sì' : 'No' }}</li>
                                @if($request->andata_ritorno)
                                    <li class="mb-1"><strong class="font-semibold w-32 inline-block">Data Ritorno:</strong> {{ $request->data_ritorno ? \Carbon\Carbon::parse($request->data_ritorno)->format('d/m/Y') : 'N/A' }}</li>
                                    <li><strong class="font-semibold w-32 inline-block">Orario Ritorno:</strong> {{ $request->orario_ritorno ?? 'N/A' }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Finestra Chat / Conversazione -->
                    <div class="mb-8">
                        <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-3">Cronologia Messaggi</h3>
                        <div class="border border-gray-200 rounded-lg p-6 space-y-6 max-h-[500px] overflow-y-auto bg-gray-50/50">
                            
                            <!-- Messaggio Iniziale del Cliente -->
                            <div class="flex items-start space-x-3 max-w-[85%]">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow">
                                    {{ strtoupper(substr($request->nome, 0, 1)) }}
                                </div>
                                <div class="bg-indigo-50 border border-indigo-100/50 rounded-2xl rounded-tl-none p-4 text-gray-800 shadow-sm text-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-indigo-955">{{ $request->nome }} {{ $request->cognome }}</span>
                                        <span class="text-[10px] text-indigo-500 ml-4">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="whitespace-pre-line">{{ $request->messaggio ?? 'Richiesta di transfer inviata.' }}</p>
                                </div>
                            </div>

                            <!-- Lista risposte nel thread -->
                            @foreach($request->messages as $msg)
                                @if($msg->sender === 'admin')
                                    <!-- Messaggio Supporto (Allineato a Destra) -->
                                    <div class="flex items-start justify-end space-x-3 max-w-[85%] ml-auto">
                                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tr-none p-4 text-gray-800 shadow-sm text-sm">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-gray-955 mr-4">Tu (Amministratore)</span>
                                                <span class="text-[10px] text-gray-500">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <p class="whitespace-pre-line text-gray-700">{{ $msg->message }}</p>

                                            @if($msg->attachment_path)
                                                <div class="mt-3 pt-2 border-t border-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 text-gray-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline truncate max-w-[200px]">
                                                        {{ $msg->attachment_name ?? 'Download allegato' }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center font-bold text-xs shadow">
                                            AD
                                        </div>
                                    </div>
                                @else
                                    <!-- Messaggio Cliente (Allineato a Sinistra) -->
                                    <div class="flex items-start space-x-3 max-w-[85%]">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow">
                                            {{ strtoupper(substr($request->nome, 0, 1)) }}
                                        </div>
                                        <div class="bg-indigo-50 border border-indigo-100/50 rounded-2xl rounded-tl-none p-4 text-gray-800 shadow-sm text-sm">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-indigo-955">{{ $request->nome }} {{ $request->cognome }}</span>
                                                <span class="text-[10px] text-indigo-500 ml-4">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <p class="whitespace-pre-line">{{ $msg->message }}</p>

                                            @if($msg->attachment_path)
                                                <div class="mt-3 pt-2 border-t border-indigo-100 flex items-center">
                                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline truncate max-w-[200px]">
                                                        {{ $msg->attachment_name ?? 'Download allegato' }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>

                    <!-- Modulo per rispondere -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                        <h3 class="text-sm font-bold text-gray-800 uppercase mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            Scrivi una risposta al cliente
                        </h3>
                        <form action="{{ route('admin.transfers.reply', $request) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <textarea name="message" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Scrivi qui il testo del messaggio da inviare..."></textarea>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <!-- Upload File -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Aggiungi allegato (Max 10MB)</label>
                                    <input type="file" name="attachment" class="text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>

                                <!-- Pulsante -->
                                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                                    Invia Risposta per Email
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Bottom Bar (Actions) -->
                    <div class="mt-8 pt-4 border-t flex justify-between items-center">
                        <span class="text-xs text-gray-400">Token di sicurezza: {{ $request->secure_token }}</span>
                        <form action="{{ route('admin.transfers.destroy', $request) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questa richiesta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 px-3 rounded text-xs focus:outline-none focus:shadow-outline">
                                Elimina Conversazione
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
