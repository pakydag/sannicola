@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">Le Nostre Strutture</h1>
                <p class="mt-4 text-xl text-gray-500">Prenota il tuo soggiorno ideale in una delle nostre fantastiche location.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($structures as $s)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full border border-gray-100">
                        <!-- Immagine con Overlay -->
                        <div class="relative h-64">
                            @if($s->photos->count() > 0)
                                <img src="{{ asset($s->photos->first()->path) }}" alt="{{ $s->nome }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-indigo-50 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-indigo-700 font-bold text-sm shadow-sm">
                                Da configurare nei periodi
                            </div>
                        </div>

                        <!-- Contenuto -->
                        <div class="p-6 flex-1 flex flex-col">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2 truncate">{{ $s->nome }}</h2>
                            
                            <div class="flex items-center gap-4 text-gray-500 text-sm mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    {{ $s->camere_letto }} Camere
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ $s->posti_totali }} Ospiti
                                </span>
                            </div>

                            <p class="text-gray-600 line-clamp-2 text-sm mb-6">
                                {!! strip_tags($s->descrizione) !!}
                            </p>

                            <div class="mt-auto">
                                <a href="{{ route('public.booking.show', $s->id) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all">
                                    @if($s->prenotabile)
                                        Scopri e Prenota
                                    @else
                                        Scopri di più
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 text-lg">Al momento non ci sono strutture disponibili.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
