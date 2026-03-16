<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dettaglio Prenotazione #{{ $booking->id }}
            </h2>
            <a href="{{ route('admin.booking.bookings.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Torna all'elenco</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Cliente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-1">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Cliente</h3>
                        @if($booking->customer)
                            <p class="font-bold text-lg text-indigo-700">{{ $booking->customer->nome }} {{ $booking->customer->cognome }}</p>
                            <p class="text-gray-600">{{ $booking->customer->email }}</p>
                            <p class="text-gray-600">{{ $booking->customer->telefono ?? 'N/D' }}</p>
                            <div class="mt-4 p-3 bg-gray-50 rounded border">
                                <p class="text-xs uppercase text-gray-400 font-bold">Località</p>
                                <p class="text-sm">
                                    {{ $booking->customer->citta ?? '-' }} ({{ $booking->customer->nazione ?? '-' }})
                                </p>
                            </div>
                        @else
                            <p class="text-red-500 italic">Cliente non trovato o eliminato.</p>
                        @endif
                    </div>
                </div>

                <!-- Info Prenotazione -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-6">
                                @if($booking->structure && $booking->structure->photos->count() > 0)
                                    <img src="{{ asset($booking->structure->photos->first()->path) }}" class="h-24 w-24 rounded-lg object-cover shadow-sm">
                                @else
                                    <div class="h-24 w-24 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-dashed">
                                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $booking->structure->nome ?? 'Struttura Eliminata' }}</p>
                                    <p class="text-xs text-gray-500 mt-1 italic">{{ $booking->structure->indirizzo ?? '' }} {{ $booking->structure->citta ?? '' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                                <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                    <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider mb-1">Check-in</p>
                                    <p class="text-lg font-bold text-indigo-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                    <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider mb-1">Check-out</p>
                                    <p class="text-lg font-bold text-indigo-900">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl">
                                    <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider mb-1">Ospiti</p>
                                    <p class="text-lg font-bold text-amber-900">{{ $booking->adulti + $booking->bambini }}</p>
                                    <p class="text-[10px] text-amber-600">({{ $booking->adulti }} ad. @if($booking->bambini > 0), {{ $booking->bambini }} bam.@endif)</p>
                                </div>
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Totale</p>
                                    <p class="text-lg font-bold text-slate-900">€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</p>
                                    <p class="text-[10px] text-slate-500 tracking-tight">{{ ucfirst($booking->metodo_pagamento ?? 'N/D') }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4 items-center border-t pt-8">
                                <div class="flex-grow">
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Gestione Stati</p>
                                    <div class="flex gap-3">
                                        @if($booking->stato_pagamento != 'pagato')
                                            <form action="{{ route('admin.booking.bookings.mark_as_paid', $booking) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-md transition-all">
                                                    Segna come Pagato
                                                </button>
                                            </form>
                                        @endif

                                        @if($booking->stato != 'annullato')
                                            <form action="{{ route('admin.booking.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler annullare questa prenotazione? Verrà inviata una notifica al cliente.')">
                                                @csrf
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold py-2 px-4 rounded-lg transition-all">
                                                    Annulla Prenotazione
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <span class="px-4 py-2 rounded-full border font-bold text-xs text-center {{ match($booking->stato) {
                                        'confermato' => 'text-green-600 bg-green-50 border-green-200',
                                        'annullato' => 'text-red-600 bg-red-50 border-red-200',
                                        default => 'text-amber-600 bg-amber-50 border-amber-200'
                                    } }}">
                                        STATO: {{ strtoupper($booking->stato) }}
                                    </span>
                                    <span class="px-4 py-2 rounded-full border font-bold text-xs text-center {{ $booking->stato_pagamento == 'pagato' ? 'text-indigo-600 bg-indigo-50 border-indigo-200' : 'text-gray-500 bg-gray-50 border-gray-100' }}">
                                        PAGAMENTO: {{ strtoupper($booking->stato_pagamento) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
