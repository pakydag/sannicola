<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dettaglio Richiesta da') }} {{ $contatto->nome }} {{ $contatto->cognome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 flex justify-between items-center border-b pb-4">
                        <div>
                            <span class="text-gray-500 text-sm">Ricevuta il: {{ $contatto->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <a href="{{ route('admin.contatti.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                            &larr; Torna all'elenco
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-2">Dati Mittente</h3>
                            <ul class="text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <li class="mb-2"><strong class="font-semibold w-32 inline-block">Nome e Cognome:</strong> {{ $contatto->nome }} {{ $contatto->cognome }}</li>
                                @if($contatto->ragione_sociale)
                                    <li class="mb-2"><strong class="font-semibold w-32 inline-block">Rag. Sociale:</strong> {{ $contatto->ragione_sociale }}</li>
                                @endif
                                <li class="mb-2">
                                    <strong class="font-semibold w-32 inline-block">Email:</strong> 
                                    <a href="mailto:{{ $contatto->email }}" class="text-indigo-600 hover:underline">{{ $contatto->email }}</a>
                                </li>
                                @if($contatto->telefono)
                                    <li>
                                        <strong class="font-semibold w-32 inline-block">Telefono:</strong> 
                                        <a href="tel:{{ $contatto->telefono }}" class="text-indigo-600 hover:underline">{{ $contatto->telefono }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-2">Messaggio / Richiesta</h3>
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-gray-800 whitespace-pre-wrap leading-relaxed">
                            {{ $contatto->richiesta }}
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <form action="{{ route('admin.contatti.destroy', $contatto) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questa richiesta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Elimina Messaggio
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
