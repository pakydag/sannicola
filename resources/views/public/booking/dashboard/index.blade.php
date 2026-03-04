@extends('public.layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar / Menu -->
            <div class="w-full md:w-64 space-y-2">
                <a href="{{ route('public.booking.dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Le mie Prenotazioni
                </a>
                <a href="{{ route('public.booking.dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 bg-white text-gray-700 hover:bg-gray-100 rounded-xl font-bold transition-all border border-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Il mio Profilo
                </a>
                <form action="{{ route('public.booking.dashboard.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 bg-white text-red-600 hover:bg-red-50 rounded-xl font-bold transition-all border border-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Esci
                    </button>
                </form>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <h1 class="text-2xl font-bold text-gray-900">Le mie Prenotazioni</h1>
                        <p class="text-gray-500 text-sm">Visualizza lo stato e i dettagli dei tuoi soggiorni.</p>
                    </div>

                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                                    <tr>
                                        <th class="px-8 py-4">Struttura & Periodo</th>
                                        <th class="px-8 py-4">Prezzo</th>
                                        <th class="px-8 py-4">Stato</th>
                                        <th class="px-8 py-4 text-right">Dettaglio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($bookings as $booking)
                                    <tr class="hover:bg-gray-50/50 transition-all">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                @if($booking->structure && $booking->structure->photos->count() > 0)
                                                    <img src="{{ asset($booking->structure->photos->first()->path) }}" class="h-12 w-12 rounded-lg object-cover">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $booking->structure->nome ?? 'Struttura non disponibile' }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="font-bold text-gray-900">€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</p>
                                            <p class="text-[10px] uppercase font-bold {{ $booking->stato_pagamento == 'pagato' ? 'text-green-600' : 'text-amber-500' }}">
                                                {{ str_replace('_', ' ', $booking->stato_pagamento) }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $statusColor = match($booking->stato) {
                                                    'confermato' => 'bg-green-100 text-green-700',
                                                    'annullato' => 'bg-red-100 text-red-700',
                                                    default => 'bg-amber-100 text-amber-700'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                                {{ $booking->stato }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            @if($booking->stripe_payment_link && $booking->stato_pagamento == 'non_pagato')
                                                <a href="{{ $booking->stripe_payment_link }}" class="text-indigo-600 hover:underline text-xs font-bold mr-4">Paga con Stripe</a>
                                            @endif
                                            {{-- Potremmo aggiungere un tasto dettaglio qui se serve una pagina specifica --}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-12 text-center">
                                            <div class="bg-gray-50 rounded-2xl p-8 border border-dashed border-gray-200">
                                                <p class="text-gray-500 italic">Non hai ancora effettuato nessuna prenotazione.</p>
                                                <a href="{{ route('public.booking.index') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Inizia ora la tua ricerca</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
