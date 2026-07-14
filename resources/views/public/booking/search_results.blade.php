@extends('public.layouts.main')

@section('title', 'Risultati Ricerca Booking - ' . config('app.name'))

@section('content')

    @if(isset($section) && $section->immagine)
        <div class="gradient relative bg-gray-50 h-64 flex items-end bg-cover bg-center px-6 lg:px-0" style="background-image: url('{{ asset($section->immagine) }}');">
            <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0 relative z-10">
                <nav class="flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
                    <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <a href="{{ route('public.booking.index') }}" class="hover:text-gray-900 transition">{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Search Results' : 'Risultati Ricerca' }}</span>
                </nav>
            </div>
        </div>
        <div class="bg-gray-50 pb-16">
            <div class="max-w-7xl mx-6 lg:mx-8 xl:mx-auto -mt-16 relative z-20 mb-12">
                <!-- Form modifica ricerca -->
                @include('public.partials.widgets.booking_search', ['widget' => (object)['data' => []]])
            </div>
    @else
        <div class="bg-gray-50 pt-12 pb-16">
            <div class="max-w-7xl mx-6 lg:mx-8 xl:mx-auto mb-12">
                <!-- Form modifica ricerca -->
                @include('public.partials.widgets.booking_search', ['widget' => (object)['data' => []]])
            </div>
    @endif

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
                                {{ app()->getLocale() === 'en' ? 'Up to' : 'Fino a' }} {{ $structure->posti_totali }} {{ app()->getLocale() === 'en' ? 'guests' : 'posti' }}
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $structure->nome }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{!! strip_tags($structure->descrizione) !!}</p>

                            <div class="flex items-center text-sm text-gray-500 mb-6 gap-4">
                                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> {{ $structure->camere_letto }} {{ app()->getLocale() === 'en' ? 'Bedrooms' : 'Camere' }}</span>
                                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg> {{ $structure->bagni }} {{ app()->getLocale() === 'en' ? 'Bathrooms' : 'Bagni' }}</span>
                            </div>

                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                @if($structure->is_available && $structure->prenotabile)
                                    <div>
                                        <span class="block text-xs text-gray-500 uppercase font-semibold">{{ app()->getLocale() === 'en' ? 'Est. Quote' : 'Preventivo Est.' }}</span>
                                        <span class="text-2xl font-semibold text-primary">€{{ number_format($structure->preventivo, 2, ',', '.') }}</span>
                                    </div>

                                    <a href="{{ route('public.booking.show', ['id' => $structure->id, 'slug' => \Illuminate\Support\Str::slug($structure->nome)]) }}?start_date={{ $searchParams['start_date'] }}&end_date={{ $searchParams['end_date'] }}&adulti={{ $searchParams['adulti'] }}&bambini={{ $searchParams['bambini'] }}"
                                       class="bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-xl font-bold transition shadow-md">
                                        {{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}
                                    </a>
                                @else
                                    <div class="w-full text-center py-2">
                                        <span class="inline-block w-full bg-red-100 text-red-700 px-6 py-3 rounded-xl font-bold uppercase text-sm">
                                            {{ app()->getLocale() === 'en' ? 'Not Available' : 'Non Disponibile' }}
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ app()->getLocale() === 'en' ? 'No Available Structures' : 'Nessuna Struttura Disponibile' }}</h3>
                <p class="text-gray-500 max-w-md mx-auto">{{ app()->getLocale() === 'en' ? 'We are sorry, but we did not find any available structures for the selected dates and guests. Try modifying your search criteria.' : 'Siamo spiacenti, ma non abbiamo trovato strutture libere per le date e gli ospiti selezionati. Prova a modificare i criteri di ricerca.' }}</p>
            </div>
        @endif
    </div>
    @if(isset($section) && $section->immagine)
        </div>
    @endif
@endsection
