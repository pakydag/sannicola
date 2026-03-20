@extends('public.layouts.main')

@section('title', $sezione->nome . ' - ' . config('app.name'))

@section('content')
<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                {{ $sezione->nome }}
            </h1>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    @if($sezione->contenuto)
    <div class="prose prose-indigo prose-lg mx-auto text-gray-500 mb-16 max-w-none">
        {!! $sezione->contenuto !!}
    </div>
    @endif

    @if($sezione->tipo !== 'pagina')
        @if($articoli->count() > 0)
            <div class="border-t border-gray-200 pt-10">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8 text-center">Articoli in questa sezione</h2>
                
                @php
                    // Mappa il numero di colonne selezionato alle classi Tailwind complete
                    $gridClass = 'grid-cols-1 md:grid-cols-3'; // Default 3 colonne
                    switch($sezione->colonne_griglia) {
                        case 1: $gridClass = 'grid-cols-1'; break;
                        case 2: $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-2'; break;
                        case 3: $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'; break;
                        case 4: $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'; break;
                        case 6: $gridClass = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6'; break;
                    }
                @endphp

                <div class="grid {{ $gridClass }} gap-8">
                    @foreach($articoli as $articolo)
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden flex flex-col transition hover:shadow-md">
                            @if($articolo->hasMedia('foto'))
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block p-4 border-b">
                                    <img src="{{ $articolo->getFirstMediaUrl('foto', 'thumb') }}" alt="{{ $articolo->titolo }}" class="w-full h-48 object-cover rounded">
                                </a>
                            @endif
                            <div class="p-6 flex-grow flex flex-col pb-4 text-center items-center justify-center">
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block mt-2">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $articolo->titolo }}</h3>
                                    @if($articolo->sottotitolo)
                                        <p class="mt-3 text-base text-gray-500">{{ Str::limit($articolo->sottotitolo, 100) }}</p>
                                    @endif
                                </a>
                            </div>
                            <div class="pb-6 px-6 text-center">
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Leggi di più &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 max-w-xl mx-auto">
                    {{ $articoli->links() }}
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
