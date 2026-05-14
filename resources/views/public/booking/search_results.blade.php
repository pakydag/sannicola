@extends('public.layouts.main')

@section('title', 'Risultati Ricerca Booking - ' . config('app.name'))

@section('content')

<div class="bg-indigo-700 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-white sm:text-4xl text-center mb-6">
            Strutture Disponibili
        </h1>
        <p class="text-indigo-100 text-center text-lg max-w-2xl mx-auto mb-8">
            Dal {{ \Carbon\Carbon::parse($searchParams['start_date'])->format('d/m/Y') }}
            al {{ \Carbon\Carbon::parse($searchParams['end_date'])->format('d/m/Y') }}
            per {{ $searchParams['adulti'] }} Adult{{ $searchParams['adulti'] > 1 ? 'i' : 'o' }}
            @if($searchParams['bambini'] > 0)
                e {{ $searchParams['bambini'] }} Bambin{{ $searchParams['bambini'] > 1 ? 'i' : 'o' }}
            @endif
        </p>

        <!-- Form modifica ricerca -->
        <div class="bg-white rounded-xl shadow-lg p-4 max-w-4xl mx-auto">
            <form action="{{ route('public.booking.search') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-1/4">
                    <label class="block text-xs text-gray-500 font-bold mb-1">Check-in</label>
                    <input type="date" name="start_date" value="{{ $searchParams['start_date'] }}" min="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-full md:w-1/4">
                    <label class="block text-xs text-gray-500 font-bold mb-1">Check-out</label>
                    <input type="date" name="end_date" value="{{ $searchParams['end_date'] }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-full md:w-1/6">
                    <label class="block text-xs text-gray-500 font-bold mb-1">Adulti</label>
                    <select name="adulti" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $searchParams['adulti'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-full md:w-1/6">
                    <label class="block text-xs text-gray-500 font-bold mb-1">Bambini</label>
                    <select name="bambini" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $searchParams['bambini'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-full md:w-auto flex-grow flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        Modifica
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($structures->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($structures as $structure)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                    <div class="relative h-64">
                        @if($structure->photos->count() > 0)
                            <img src="{{ asset($structure->photos->first()->path) }}" alt="{{ $structure->nome }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-sm">
                            Fino a {{ $structure->posti_totali }} posti
                        </div>
                    </div>

                    <div class="p-6 flex-grow flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $structure->nome }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{!! strip_tags($structure->descrizione) !!}</p>

                        <div class="flex items-center text-sm text-gray-500 mb-6 gap-4">
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> {{ $structure->camere_letto }} Camere</span>
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg> {{ $structure->bagni }} Bagni</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                            @if($structure->prenotabile)
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase font-semibold">Preventivo Est.</span>
                                    <span class="text-2xl font-black text-indigo-600">€{{ number_format($structure->preventivo, 2, ',', '.') }}</span>
                                </div>

                                <a href="{{ route('public.booking.show', $structure->id) }}?start_date={{ $searchParams['start_date'] }}&end_date={{ $searchParams['end_date'] }}&adulti={{ $searchParams['adulti'] }}&bambini={{ $searchParams['bambini'] }}"
                                   class="bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-xl font-bold transition shadow-md">
                                    Prenota
                                </a>
                            @else
                                <div class="w-full text-center py-2">
                                    <span class="inline-block w-full bg-red-100 text-red-700 px-6 py-3 rounded-xl font-bold uppercase text-sm">
                                        Non Disponibile
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna Struttura Disponibile</h3>
            <p class="text-gray-500 max-w-md mx-auto">Siamo spiacenti, ma non abbiamo trovato strutture libere per le date e gli ospiti selezionati. Prova a modificare i criteri di ricerca.</p>
        </div>
    @endif
</div>

@endsection
