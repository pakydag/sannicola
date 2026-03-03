<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking - Prenotazioni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">Cliente</th>
                                    <th class="py-2 px-4 border-b text-left">Struttura</th>
                                    <th class="py-2 px-4 border-b text-center">Periodo</th>
                                    <th class="py-2 px-4 border-b text-center">Persone</th>
                                    <th class="py-2 px-4 border-b text-right">Totale</th>
                                    <th class="py-2 px-4 border-b text-center">Stato</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $b)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-sm text-gray-500">#{{ $b->id }}</td>
                                        <td class="py-2 px-4 border-b font-bold">{{ $b->customer->nome ?? 'N/D' }} {{ $b->customer->cognome ?? '' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $b->structure->nome ?? 'N/D' }}</td>
                                        <td class="py-2 px-4 border-b text-center text-sm">
                                            {{ \Carbon\Carbon::parse($b->start_date)->format('d/m/Y') }} <br>
                                            {{ \Carbon\Carbon::parse($b->end_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-2 px-4 border-b text-center text-xs">
                                            {{ $b->adulti }} Adulti @if($b->bambini > 0), {{ $b->bambini }} Bambini @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right font-mono font-bold text-indigo-700">
                                            €{{ number_format($b->totale_prezzo, 2, ',', '.') }}
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @php
                                                $statusClass = match($b->stato) {
                                                    'confermato' => 'bg-green-100 text-green-800',
                                                    'annullato' => 'bg-red-100 text-red-800',
                                                    default => 'bg-amber-100 text-amber-800'
                                                };
                                                $paymentStatusClass = $b->stato_pagamento == 'pagato' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 block mb-1 text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($b->stato) }}
                                            </span>
                                            <span class="px-2 block text-xs leading-5 font-semibold rounded-full {{ $paymentStatusClass }}">
                                                {{ ucfirst($b->stato_pagamento) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.booking.bookings.show', $b) }}" class="text-indigo-600 hover:text-indigo-900">Dettaglio</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-4 px-4 text-center text-gray-500">Nessuna prenotazione trovata.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
