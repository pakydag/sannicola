@php
    $s = $structure;
@endphp

<div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full border border-gray-100">
    <!-- Immagine -->
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
    </div>

    <!-- Contenuto -->
    <div class="p-6 flex-1 flex flex-col">
        <h2 class="text-2xl font-bold text-gray-900 mb-2 truncate">{{ $s->nome }}</h2>
        
        @if($s->prenotabile)
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
        @endif

        <p class="text-gray-600 line-clamp-2 text-sm mb-6">
            {!! strip_tags($s->descrizione) !!}
        </p>

        <div class="mt-auto">
            @if($s->prenotabile)
                <a href="{{ route('public.booking.show', $s->id) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all">
                    Scopri e Prenota
                </a>
            @else
                <div class="block w-full text-center bg-gray-100 text-gray-400 font-bold py-3 px-4 rounded-xl">
                    Non disponibile
                </div>
            @endif
        </div>
    </div>
</div>
