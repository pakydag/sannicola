<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dettaglio Ticket AI') }} #{{ $ticket->id }}
            </h2>
            <a href="{{ route('admin.vapi.tickets.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Torna all'elenco</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Colonna Sinistra: Info Principali -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Dettagli Conversazione</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Data Apertura</p>
                                    <p class="text-sm text-gray-900 font-medium">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Reparto / Tipo</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $ticket->assistance_type }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Descrizione Problema</p>
                                <div class="p-4 bg-gray-50 rounded-md border border-gray-100 text-sm text-gray-700">
                                    {{ $ticket->description }}
                                </div>
                            </div>

                            <!-- Box Commenti -->
                            <div class="mb-6">
                                <h4 class="text-sm font-bold text-slate-700 mb-2 uppercase tracking-tight">Commenti Interni</h4>
                                @if(session('success'))
                                    <div class="mb-3 p-2 bg-green-50 border border-green-200 text-green-700 text-xs rounded">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('admin.vapi.tickets.update-comments', $ticket) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="comments" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Inserisci qui annotazioni o commenti interni...">{{ $ticket->comments }}</textarea>
                                    <div class="mt-2 text-right">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded text-xs font-semibold hover:bg-indigo-700 transition-colors">
                                            Salva Commenti
                                        </button>
                                    </div>
                                </form>
                            </div>

                            @if($ticket->transcription)
                                <div class="mb-6">
                                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Trascrizione Telefonata</p>
                                    <div class="p-4 bg-slate-50 rounded-md border border-slate-200 text-sm text-slate-800 max-h-96 overflow-y-auto whitespace-pre-wrap leading-relaxed italic">
                                        {{ $ticket->transcription }}
                                    </div>
                                </div>
                            @endif

                            @if($ticket->audio_url)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Registrazione Audio</p>
                                    <audio controls class="w-full">
                                        <source src="{{ $ticket->audio_url }}" type="audio/mpeg">
                                        Il tuo browser non supporta l'elemento audio.
                                    </audio>
                                    <div class="mt-2 text-right">
                                        <a href="{{ $ticket->audio_url }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-800">Scarica file audio &rarr;</a>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-amber-50 rounded-md border border-amber-200 text-xs text-amber-700">
                                    Registrazione audio non ancora disponibile.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonna Destra: Cliente e Azioni -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Cliente</h3>
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Nome</p>
                                @if($ticket->contact_id)
                                    <a href="{{ route('admin.customers.show', $ticket->contact_id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold">
                                        {{ $ticket->contact->full_name }}
                                    </a>
                                @else
                                    <p class="text-sm text-gray-900 font-bold">{{ $ticket->customer_name }}</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Azienda</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $ticket->company_name }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Email</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $ticket->email ?? 'Non fornita' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Telefono</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $ticket->phone ?? 'Non disponibile' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Gestione Stato</h3>
                            
                            <div class="mb-6">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Stato Attuale</p>
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $ticket->status === 'open' ? 'Aperto' : 'Chiuso' }}
                                </span>
                                @if($ticket->closed_at)
                                    <p class="text-xs text-gray-400 mt-2 italic">Chiuso il: {{ $ticket->closed_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>

                            @if($ticket->status === 'open')
                                <form action="{{ route('admin.vapi.tickets.close', $ticket) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-semibold shadow-sm transition-colors text-sm">
                                        Chiudi Ticket
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.vapi.tickets.destroy', $ticket) }}" method="POST" class="mt-4 pt-4 border-t" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo ticket?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-white border border-red-600 text-red-600 px-4 py-2 rounded-md hover:bg-red-600 hover:text-white font-semibold transition-all text-sm">
                                    Elimina Ticket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
