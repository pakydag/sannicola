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
                            <p class="text-gray-600">{{ $booking->customer->telefono }} / {{ $booking->customer->cellulare }}</p>
                            <div class="mt-4 p-3 bg-gray-50 rounded border">
                                <p class="text-xs uppercase text-gray-400 font-bold">Indirizzo</p>
                                <p class="text-sm">
                                    {{ $booking->customer->indirizzo }}<br>
                                    {{ $booking->customer->cap }} {{ $booking->customer->citta }} ({{ $booking->customer->provincia }})
                                </p>
                            </div>
                        @else
                            <p class="text-red-500 italic">Cliente non trovato or eliminato.</p>
                        @endif
                    </div>
                </div>

                <!-- Info Prenotazione -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold border-b pb-2 mb-4">Dettagli Soggiorno</h3>
                                <p class="text-2xl font-bold">{{ $booking->structure->nome ?? 'Struttura Eliminata' }}</p>
                                <div class="flex gap-8 mt-4">
                                    <div class="text-center p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                                        <p class="text-xs text-indigo-400 font-bold uppercase">Check-in</p>
                                        <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-center p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                                        <p class="text-xs text-indigo-400 font-bold uppercase">Check-out</p>
                                        <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-center p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs text-gray-400 font-bold uppercase">Ospiti</p>
                                        <p class="text-lg font-bold">{{ $booking->adulti + $booking->bambini }}</p>
                                        <p class="text-xs text-gray-500">({{ $booking->adulti }} ad. @if($b->bambini > 0), {{ $b->bambini }} bam.@endif)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold uppercase">Totale Pagato</p>
                                <p class="text-3xl font-bold text-indigo-600">€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">Metodo: {{ ucfirst($booking->metodo_pagamento ?? 'N/D') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                           <div class="border rounded p-4">
                               <p class="font-bold mb-2">Stato Prenotazione</p>
                               @php
                                   $statusColor = match($booking->stato) {
                                       'confermato' => 'text-green-600 bg-green-50 border-green-200',
                                       'annullato' => 'text-red-600 bg-red-50 border-red-200',
                                       default => 'text-amber-600 bg-amber-50 border-amber-200'
                                   };
                               @endphp
                               <span class="px-4 py-2 rounded-full border font-bold text-sm {{ $statusColor }}">
                                   {{ strtoupper($booking->stato) }}
                               </span>
                           </div>
                           <div class="border rounded p-4">
                               <p class="font-bold mb-2">Stato Pagamento</p>
                               @php
                                   $payStatusColor = $booking->stato_pagamento == 'pagato' ? 'text-indigo-600 bg-indigo-50 border-indigo-200' : 'text-gray-600 bg-gray-50 border-gray-200';
                               @endphp
                               <span class="px-4 py-2 rounded-full border font-bold text-sm {{ $payStatusColor }}">
                                   {{ strtoupper($booking->stato_pagamento) }}
                               </span>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
