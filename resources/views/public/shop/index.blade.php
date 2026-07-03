@extends('public.layouts.main')

@section('title', 'Shop - ' . config('app.name'))

@section('content')
@if(isset($section) && $section->immagine)
<div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-bg-top bg-fixed px-6 lg:px-0" style="background-image: url('{{ asset($section->immagine) }}');">
    <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg">
        <nav class=" flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <span class="text-gray-900">Shop</span>
        </nav>
    </div>
</div>
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-12 border-t-0">
    <div class="text-center titoli">
        <h1 class="text-4xl sm:text-5xl">
            Il Nostro Shop
        </h1>
        <h2 class="mt-4 max-w-2xl mx-auto">
            Esplora le nostre collezioni e categorie per trovare i migliori prodotti.
</h2>
    </div>
</div>
@else
<div class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl">
                Il Nostro Shop
            </h1>
            <h2 class="mt-4 max-w-2xl mx-auto">
                Esplora le nostre collezioni e categorie per trovare i migliori prodotti.
</h2>
        </div>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/5 flex-shrink-0">
            @include('public.shop.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-grow">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                    @if($filtered)
                        Risultati per: {{ $marca ? $marca->nome : ($categoria ? $categoria->nome : 'Filtro') }}
                    @else
                        Le Nostre Collezioni
                    @endif
                </div>

                @if($filtered)
                    <a href="{{ route('public.shop.index') }}" class="text-xs font-bold text-red-500 hover:text-red-700 underline uppercase tracking-widest">Rimuovi Filtro</a>
                @endif
            </h2>

            @if($filtered)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($prodotti as $prodotto)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition-all hover:shadow-xl hover:-translate-y-1 group">
                            @php
                                $primaFoto = is_array($prodotto->foto_aggiuntive) && count($prodotto->foto_aggiuntive) > 0 ? $prodotto->foto_aggiuntive[0] : null;
                            @endphp

                            <div class="relative overflow-hidden aspect-[4/3]">
                                @if($primaFoto)
                                    <img src="{{ $primaFoto }}" alt="{{ $prodotto->nome }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex-grow flex flex-col">
                                @if($prodotto->marca)
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->marca }}</span>
                                @endif

                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-4 truncate">{{ $prodotto->nome }}</h3>

                                <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                                    @php
                                        $variant = $prodotto->variants->first();
                                        $prezzo = $variant ? $variant->prezzo : 0;
                                        $prezzo_scontato = $variant ? $variant->prezzo_scontato : null;

                                        $showPreview = ($settings['shop_price_preview'] ?? '0') == '1';
                                        if ($showPreview) {
                                            $priceData = $prodotto->getLowestPrice();
                                            $prezzo = $priceData['prezzo'];
                                            $prezzo_scontato = $priceData['prezzo_scontato'];
                                        }
                                    @endphp

                                    <div class="flex flex-col">
                                        @if($showPreview)
                                            @if($prezzo_scontato > 0)
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-black text-red-600 leading-none">€ {{ number_format($prezzo_scontato, 2, ',', '.') }}</span>
                                                    <span class="text-xs line-through text-gray-400 mt-1">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                                </div>
                                            @elseif($prezzo > 0)
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-black text-gray-900 leading-none">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Dettagli</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $prodotto->collection?->slug ?? 'all', 'prodotto_slug' => $prodotto->slug]) }}" class="inline-flex items-center justify-center w-10 h-10 bg-gray-900 text-white rounded-xl hover:bg-indigo-600 transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-400 italic bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            Nessun prodotto trovato per questo filtro.
                        </div>
                    @endforelse
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($collezioni as $collezione)
                        <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
                            <div class="aspect-[16/9] overflow-hidden">
                                @if($collezione->foto)
                                    <img src="{{ $collezione->foto }}" alt="{{ $collezione->nome }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                            </div>

                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white text-center">
                                <h3 class="text-2xl font-bold">{{ $collezione->nome }}</h3>
                                <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="bottone-personalizzato2 mt-4 inline-flex items-center text-sm font-bold bg-white text-gray-900 px-6 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                    Esplora Collezione
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-500">
                            Nessuna collezione disponibile.
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
