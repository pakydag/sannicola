@extends('public.layouts.main')

@section('title', $sezione->nome . ' - ' . config('app.name'))

@section('content')
<div class="gradient relative bg-gray-50 h-64 flex items-end bg-top bg-center bg-cover px-6 lg:px-0 bg-no-repeat" @if($sezione->immagine) style="background-image: url('{{ asset($sezione->immagine) }}');" @endif>
    <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0 relative z-10">
        <!-- Breadcrumb / Back link -->
        <nav class=" flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <span>{{ $sezione->nome }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-6 lg:mx-auto py-6 px-11 sm:px-12 lg:px-14 bg-white shadow-sm rounded-b-lg mb-12 relative z-10">
    <header class="titoli text-center mb-10">
        <h1 class="text-4xl sm:text-5xl tracking-tight primary">
            {{ $sezione->nome }}
        </h1>
        @if($sezione->sottotitolo)
            <h2 class="mt-4 text-xl">
                {{ $sezione->sottotitolo }}
            </h2>
        @endif
    </header>

    @php
        $alignment = $sezione->allineamento_media ?? 'center';
        $alignmentClasses = 'w-full rounded-xl bg-gray-50 object-cover max-h-[600px] shadow-lg ring-1 ring-gray-200 mb-10';

        if ($alignment === 'left') {
            $alignmentClasses = 'md:float-left md:mr-8 md:mb-6 md:w-1/2 w-full rounded-lg bg-gray-50 object-cover max-h-[500px] shadow-lg ring-1 ring-gray-200';
        } elseif ($alignment === 'right') {
            $alignmentClasses = 'md:float-right md:ml-8 md:mb-6 md:w-1/2 w-full rounded-xl bg-gray-50 object-cover max-h-[500px] shadow-lg ring-1 ring-gray-200';
        }
    @endphp

    @if($sezione->foto)
        <figure class="{{ $alignment === 'center' ? 'my-10 text-center' : '' }}">
            <img class="{{ $alignmentClasses }}" src="{{ asset($sezione->foto) }}" alt="{{ $sezione->nome }}">
        </figure>
    @endif

    @if($sezione->contenuto)
    <div class="{{ $alignment === 'center' ? 'mx-auto' : 'max-w-6xl' }} mb-16 max-w-4xl mt-10 md:mt-0">
        {!! $sezione->contenuto !!}
    </div>
    @endif

    @if($alignment !== 'center')
        <div class="clear-both"></div>
    @endif
</div>
<div class="max-w-7xl mx-auto mx-6 lg:mx-auto">
    @if($sezione->tipo !== 'pagina')
        @if($articoli->count() > 0)
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8 text-center hidden">Articoli in questa sezione</h2>

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
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col transition hover:shadow-md">
                            @if($articolo->hasMedia('foto'))
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block">
                                    <img src="{{ $articolo->getFirstMediaUrl('foto', 'thumb') }}" alt="{{ $articolo->titolo }}" class="w-full h-48 object-cover rounded">
                                </a>
                            @endif
                            <div class="titoli p-6 flex-grow flex flex-col pb-4 text-center items-center justify-center">
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block mt-2">
                                    <h1 class="text-xl font-normal text-gray-900">{{ $articolo->titolo }}</h1>
                                    @if($articolo->sottotitolo)
                                        <h2 class="mt-3 text-base primary">{{ Str::limit($articolo->sottotitolo, 100) }}</h2>
                                    @endif
                                </a>
                            </div>
                            <div class="pb-6 px-6 text-center">
                                <a href="{{ route('public.articolo', ['sezione_slug' => $sezione->slug ?? $sezione->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="btn mb-5">Leggi di più &rarr;</a>
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
