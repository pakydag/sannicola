@extends('public.layouts.main')

@section('title', $categoria->nome . ' - Shop ' . config('app.name'))

@section('content')
@php
    $shopSection = \App\Models\Section::where('modulo', 'shop')->first();
    $bannerImmagine = $shopSection ? $shopSection->immagine : null;
@endphp

<div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-bg-top bg-fixed px-6 lg:px-0" style="background-image: url('{{ $bannerImmagine ? asset($bannerImmagine) : '' }}');">
    <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg">
        <nav class="flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900 font-medium">Home</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <a href="{{ route('public.shop.index') }}" class="hover:text-gray-900 font-medium">Shop</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <span class="text-gray-900 font-medium">{{ $categoria->nome }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-12 border-t-0">
    <div class="text-center titoli">
        <h1 class="text-3xl sm:text-4xl">
            {{ $categoria->nome }}
        </h1>
        <h2>
            Sfoglia i nostri prodotti della categoria {{ $categoria->nome }}.
        </h2>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/5 flex-shrink-0">
            @include('public.shop.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-grow">
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
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>

                            @if($prodotto->variants->first() && $prodotto->variants->first()->prezzo_scontato > 0)
                                <div class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full shadow-lg">Saldi</div>
                            @endif
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            @if($prodotto->marca)
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->marca }}</span>
                            @endif

                            <h3 class="text-lg font-normal text-primary group-hover:text-indigo-600 transition-colors mb-4 truncate">{{ $prodotto->nome }}</h3>

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
                                                @if($prodotto->variants->count() > 1)
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold mb-1">A partire da</span>
                                                @endif
                                                <span class="text-lg font-black text-red-600 leading-none">€ {{ number_format($prezzo_scontato, 2, ',', '.') }}</span>
                                                <span class="text-xs line-through text-gray-400 mt-1">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                            </div>
                                        @elseif($prezzo > 0)
                                            <div class="flex flex-col">
                                                @if($prodotto->variants->count() > 1)
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold mb-1">A partire da</span>
                                                @endif
                                                <span class="text-lg font-normal text-gray-900 leading-none">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm font-normal text-gray-400 italic">Prezzo su richiesta</span>
                                        @endif
                                    @else
                                        <span class="text-sm font-normal text-gray-400 uppercase tracking-widest text-indigo-500">Vedi Dettagli</span>
                                    @endif
                                </div>

                                <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $prodotto->collection?->slug ?? 'generale', 'prodotto_slug' => $prodotto->slug]) }}" class="inline-flex items-center justify-center w-10 h-10 bg-gray-900 text-white rounded-xl hover:bg-indigo-600 transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Nessun prodotto trovato</h3>
                        <p class="text-gray-500 text-sm mt-1">Non ci sono ancora prodotti assegnati a questa categoria.</p>
                        <a href="{{ route('public.shop.index') }}" class="mt-6 inline-flex text-indigo-600 font-bold hover:underline">Torna allo Shop</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
