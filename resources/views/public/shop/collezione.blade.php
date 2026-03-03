@extends('public.layouts.main')

@section('title', $collezione->nome . ' - Shop ' . config('app.name'))

@section('content')
<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Collezione: {{ $collezione->nome }}
            </h1>
            <div class="mt-4">
                <a href="{{ route('public.shop.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">&larr; Torna alle collezioni</a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($prodotti as $prodotto)
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden flex flex-col transition hover:shadow-md">
                @php
                    $primaFoto = is_array($prodotto->foto_aggiuntive) && count($prodotto->foto_aggiuntive) > 0 ? $prodotto->foto_aggiuntive[0] : null;
                @endphp
                @if($primaFoto)
                    <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $collezione->slug, 'prodotto_slug' => $prodotto->slug]) }}" class="block p-4 border-b">
                        <img src="{{ $primaFoto }}" alt="{{ $prodotto->nome }}" class="w-full h-48 object-cover rounded">
                    </a>
                @else
                    <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $collezione->slug, 'prodotto_slug' => $prodotto->slug]) }}" class="block p-4 border-b bg-gray-100 flex items-center justify-center h-56">
                        <span class="text-gray-400">Nessuna immagine</span>
                    </a>
                @endif
                
                <div class="p-4 flex-grow flex flex-col">
                    @if($prodotto->marca)
                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ $prodotto->marca }}</span>
                    @endif
                    <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $collezione->slug, 'prodotto_slug' => $prodotto->slug]) }}" class="block mt-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $prodotto->nome }}</h3>
                    </a>
                    
                    <div class="mt-auto pt-4">
                        @php
                            $defaultPrezzo = 0;
                            $defaultPrezzoScontato = null;
                            if($prodotto->variants->count() > 0) {
                                $defaultPrezzo = $prodotto->variants->first()->prezzo;
                                $defaultPrezzoScontato = $prodotto->variants->first()->prezzo_scontato;
                            }
                        @endphp
                        
                        @if($defaultPrezzoScontato > 0)
                            <div class="flex items-center space-x-2">
                                <span class="text-xl font-bold text-red-600">€ {{ number_format($defaultPrezzoScontato, 2, ',', '.') }}</span>
                                <span class="text-sm line-through text-gray-400">€ {{ number_format($defaultPrezzo, 2, ',', '.') }}</span>
                            </div>
                        @elseif($defaultPrezzo > 0)
                            <span class="text-xl font-bold text-gray-900">€ {{ number_format($defaultPrezzo, 2, ',', '.') }}</span>
                        @else
                            <span class="text-sm font-medium text-gray-500">Vedi dettagli</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-12">
                Nessun prodotto disponibile in questa collezione al momento.
            </div>
        @endforelse
    </div>
</div>
@endsection
