<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dettaglio Appuntamento AI') }} #{{ $appointment->id }}
            </h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Torna all'elenco</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Colonna Sinistra: Info Principali -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Dettagli Appuntamento</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Giorno e Ora</p>
                                    <p class="text-sm text-gray-900 font-medium">{{ date('d/m/Y H:i', strtotime($appointment->start_time)) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Costo Chiamata</p>
                                    <p class="text-sm text-indigo-600 font-bold">
                                        @if($appointment->cost > 0)
                                            ${{ number_format($appointment->cost, 2) }}
                                        @else
                                            <span class="text-gray-400 font-normal">0.00 (N/A)</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Durata Chiamata</p>
                                    <p class="text-sm text-slate-800 font-bold">
                                        @if($appointment->duration > 0)
                                            {{ floor($appointment->duration / 60) }}:{{ str_pad($appointment->duration % 60, 2, '0', STR_PAD_LEFT) }}
                                        @else
                                            <span class="text-gray-400 font-normal">00:00 (N/A)</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Reparto</p>
                                    <span class="text-sm text-gray-900 font-bold">{{ $appointment->department->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Motivazione / Note</p>
                                <div class="p-4 bg-gray-50 rounded-md border border-gray-100 text-sm text-gray-700">
                                    {{ $appointment->description }}
                                </div>
                            </div>

                            <div class="mb-6 bg-slate-50 p-2 rounded border border-dashed border-slate-200">
                                <div class="flex justify-between items-center">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">ID Chiamata Vapi: <span class="font-mono text-slate-500">{{ $appointment->vapi_call_id ?? 'Non registrato' }}</span></p>
                                </div>
                            </div>

                            @if($appointment->transcription)
                                <div class="mb-6">
                                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Trascrizione Telefonata</p>
                                    <div class="p-4 bg-slate-50 rounded-md border border-slate-200 text-sm text-slate-800 max-h-96 overflow-y-auto whitespace-pre-wrap leading-relaxed italic">
                                        {{ $appointment->transcription }}
                                    </div>
                                </div>
                            @endif

                            @if($appointment->recording_url)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Registrazione Audio</p>
                                    <audio controls class="w-full">
                                        <source src="{{ $appointment->recording_url }}" type="audio/mpeg">
                                        Il tuo browser non supporta l'elemento audio.
                                    </audio>
                                    <div class="mt-2 text-right">
                                        <a href="{{ $appointment->recording_url }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-800">Scarica file audio &rarr;</a>
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
                                @if($appointment->contact_id)
                                    <a href="{{ route('admin.customers.show', $appointment->contact_id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold">
                                        {{ $appointment->contact->full_name }}
                                    </a>
                                @else
                                    <p class="text-sm text-gray-900 font-bold">Reso Anonimo</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Azienda</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $appointment->contact->company_name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Email</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $appointment->contact->email ?? 'Non fornita' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Telefono</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $appointment->contact->phone ?? 'Non disponibile' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Gestione Stato</h3>
                            
                            <div class="mb-6">
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Stato Attuale</p>
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $appointment->status === 'confirmed' ? 'Confermato' : 'Annullato' }}
                                </span>
                            </div>

                            @if($appointment->status === 'confirmed')
                                <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 font-semibold shadow-sm transition-colors text-sm">
                                        Annulla Appuntamento
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="mt-4 pt-4 border-t" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo appuntamento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-white border border-red-600 text-red-600 px-4 py-2 rounded-md hover:bg-red-600 hover:text-white font-semibold transition-all text-sm">
                                    Elimina record
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
