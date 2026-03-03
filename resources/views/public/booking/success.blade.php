@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 py-20 min-h-[600px] flex items-center">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="bg-white rounded-3xl shadow-2xl p-12 border border-gray-100">
                <div class="h-24 w-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Prenotazione Confermata!</h1>
                <p class="text-gray-500 text-lg mb-8">Grazie per aver scelto la nostra struttura. Abbiamo ricevuto correttamente la tua richiesta di prenotazione #{{ $booking->id }}.</p>

                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-8 text-left">
                    <h2 class="font-bold text-indigo-800 mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Info Soggiorno
                    </h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Struttura:</span>
                            <span class="font-bold text-gray-900">{{ $booking->structure->nome }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Check-in:</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Check-out:</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="text-gray-500">Stato Pagamento:</span>
                            <span class="font-bold {{ $booking->stato_pagamento == 'pagato' ? 'text-green-600' : 'text-amber-600' }}">
                                {{ ucfirst($booking->stato_pagamento) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($booking->metodo_pagamento == 'bonifico' && $booking->stato_pagamento != 'pagato')
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 mb-8 text-left">
                        <h2 class="font-bold text-amber-800 mb-2">Istruzioni per il Bonifico</h2>
                        <p class="text-xs text-amber-700 mb-4 font-medium uppercase">Effettua il bonifico entro 48 ore per confermare la prenotazione.</p>
                        <div class="space-y-1 text-sm text-amber-900 font-mono">
                            <p><strong>Intestatario:</strong> {{ \App\Models\Setting::where('key', 'bonifico_intestazione')->value('value') }}</p>
                            <p><strong>IBAN:</strong> {{ \App\Models\Setting::where('key', 'bonifico_iban')->value('value') }}</p>
                            <p><strong>Banca:</strong> {{ \App\Models\Setting::where('key', 'bonifico_banca')->value('value') }}</p>
                            <p><strong>Causale:</strong> Prenotazione #{{ $booking->id }} - {{ $booking->customer->nome }} {{ $booking->customer->cognome }}</p>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.home') }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-100 transition-all">
                        Torna in Home
                    </a>
                    <a href="#" onclick="window.print()" class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-4 px-6 rounded-2xl transition-all">
                        Stampa Ricevuta
                    </a>
                </div>
                
                <p class="mt-8 text-xs text-gray-400">Ti abbiamo inviato un'email di riepilogo a {{ $booking->customer->email }}</p>
            </div>
        </div>
    </div>
@endsection
