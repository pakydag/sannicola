@extends('public.layouts.main')

@section('title', $prodotto->nome . ' - ' . config('app.name'))

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
            <span class="text-gray-900 font-medium">{{ $prodotto->nome }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl pt-1 mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-12 border-t-0 flex items-center justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl">{{ $prodotto->nome }}</h1>
        @if($prodotto->marca)
            <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider">{{ $prodotto->marca }}</p>
        @endif
    </div>
    @if($collezione)
        <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="text-secondary hover:text-primary font-semibold text-sm">&larr; Torna a {{ $collezione->nome }}</a>
    @else
        <a href="{{ route('public.shop.index') }}" class="text-secondary hover:text-primary font-semibold text-sm">&larr; Torna allo Shop</a>
    @endif
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
        
        <!-- Galleria Immagini -->
        @once
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        @endonce
        @php
            $fotoList = is_array($prodotto->foto_aggiuntive) ? $prodotto->foto_aggiuntive : [];
        @endphp
        <div class="flex flex-col w-full"
             @if(count($fotoList) > 0)
                 x-data="{ 
                     isLightboxOpen: false, 
                     lightboxIndex: 0,
                     images: [
                         @foreach($fotoList as $foto)
                             '{{ asset($foto) }}',
                         @endforeach
                     ]
                 }"
                 @keydown.escape.window="isLightboxOpen = false"
                 @keydown.arrow-left.window="if(isLightboxOpen && images.length > 1) lightboxIndex = (lightboxIndex - 1 + images.length) % images.length"
                 @keydown.arrow-right.window="if(isLightboxOpen && images.length > 1) lightboxIndex = (lightboxIndex + 1) % images.length"
             @endif
        >
            @if(count($fotoList) > 0)
                <!-- Main Swiper Slider -->
                <div class="swiper product-main-swiper shadow-sm overflow-hidden rounded-2xl border border-gray-100 bg-white w-full h-[400px] md:h-[550px] relative">
                    <div class="swiper-wrapper">
                        @foreach($fotoList as $index => $foto)
                            <div class="swiper-slide flex items-center justify-center overflow-hidden bg-white" role="group">
                                <!-- Hover/Click Zoom Container -->
                                <div 
                                    x-data="{ zoom: false, x: 50, y: 50 }"
                                    @mouseenter="zoom = true"
                                    @mouseleave="zoom = false"
                                    @click="isLightboxOpen = true; lightboxIndex = {{ $index }}; zoom = false;"
                                    @mousemove="
                                        const rect = $el.getBoundingClientRect();
                                        x = (($event.clientX - rect.left) / rect.width) * 100;
                                        y = (($event.clientY - rect.top) / rect.height) * 100;
                                    "
                                    class="relative w-full h-full overflow-hidden flex items-center justify-center"
                                    style="cursor: zoom-in;"
                                >
                                    <img 
                                        src="{{ asset($foto) }}" 
                                        alt="{{ $prodotto->nome }}" 
                                        class="w-full h-full object-cover"
                                        style="transition: transform 0.15s ease-out;"
                                        :style="zoom ? { transform: 'scale(1.8)', transformOrigin: `${x}% ${y}%` } : { transform: 'scale(1)', transformOrigin: 'center center' }"
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($fotoList) > 1)
                        <!-- Navigation arrows -->
                        <div class="swiper-button-prev !text-indigo-600 !bg-white/80 !shadow-md !w-10 !h-10 !rounded-full after:!text-sm hover:!bg-white transition z-20"></div>
                        <div class="swiper-button-next !text-indigo-600 !bg-white/80 !shadow-md !w-10 !h-10 !rounded-full after:!text-sm hover:!bg-white transition z-20"></div>
                        <!-- Pagination -->
                        <div class="swiper-pagination"></div>
                    @endif
                </div>

                <!-- Thumbnails Grid -->
                @if(count($fotoList) > 1)
                    <div class="mt-4 grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2">
                        @foreach($fotoList as $index => $foto)
                            <button 
                                onclick="window.productSwiper.slideTo({{ $index }})"
                                class="border-2 rounded-xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 aspect-square border-transparent transition hover:opacity-90 product-thumb-btn"
                                data-index="{{ $index }}"
                            >
                                <img src="{{ asset($foto) }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Custom Full-Screen Lightbox Modal -->
                <div 
                    x-show="isLightboxOpen" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 bg-black/95 z-[100] flex flex-col justify-between p-4"
                    style="display: none;"
                >
                    <!-- Header -->
                    <div class="flex justify-between items-center p-2 border-b border-white/10 select-none">
                        <span class="text-white/80 font-medium text-sm truncate pr-4">{{ $prodotto->nome }}</span>
                        <button @click="isLightboxOpen = false" class="text-white/80 hover:text-white text-3xl font-light focus:outline-none transition">&times;</button>
                    </div>

                    <!-- Main Area -->
                    <div class="flex-grow flex items-center justify-center relative">
                        <!-- Prev Button -->
                        <button 
                            x-show="images.length > 1" 
                            @click="lightboxIndex = (lightboxIndex - 1 + images.length) % images.length" 
                            class="absolute left-2 md:left-6 text-white/50 hover:text-white text-5xl font-light focus:outline-none select-none z-50 p-3 bg-white/5 hover:bg-white/10 rounded-full transition"
                        >&lsaquo;</button>

                        <!-- Active Image -->
                        <div class="max-w-4xl max-h-[75vh] w-full h-full flex items-center justify-center p-2 md:p-6">
                            <img 
                                :src="images[lightboxIndex]" 
                                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl select-none" 
                                alt="Product detail"
                            >
                        </div>

                        <!-- Next Button -->
                        <button 
                            x-show="images.length > 1" 
                            @click="lightboxIndex = (lightboxIndex + 1) % images.length" 
                            class="absolute right-2 md:right-6 text-white/50 hover:text-white text-5xl font-light focus:outline-none select-none z-50 p-3 bg-white/5 hover:bg-white/10 rounded-full transition"
                        >&rsaquo;</button>
                    </div>

                    <!-- Footer -->
                    <div class="text-center text-white/80 text-xs font-semibold select-none pb-4">
                        Foto <span x-text="lightboxIndex + 1"></span> di <span x-text="images.length"></span>
                    </div>
                </div>
            @else
                <!-- Placeholder if no images -->
                <div class="w-full h-96 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200">
                    <span class="text-gray-400 font-medium">Nessuna immagine</span>
                </div>
            @endif
        </div>

        <!-- Dettagli e Carrello -->
        <div class="mt-10 px-4 sm:px-0 lg:mt-0" 
             x-data='productB2C(@json($prodotto->variants), @json(($settings["shop_stock_infinite"] ?? "0") == "1"))'>
            <h2 class="text-4xl">{{ $prodotto->nome }}</h2>
            @if($prodotto->sku_padre)
                <p class="text-sm text-gray-500 mt-2">SKU: {{ $prodotto->sku_padre }}</p>
            @endif

            <div class="mt-3">
                <p class="text-3xl text-gray-700" x-html="formattedPrice"></p>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Descrizione</h3>
                <div class="prose prose-sm text-gray-700">
                    {!! $prodotto->descrizione !!}
                </div>
            </div>

            <!-- Selezione B2C -->
            <div class="mt-8">
                <!-- MESSAGGI DI ERRORE O SUCCESSO -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded text-sm font-bold border border-green-200 shadow-sm flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <a href="{{ route('public.shop.cart.index') }}" class="underline hover:text-green-950 flex items-center gap-1">
                            Vai al Carrello &rarr;
                        </a>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded text-sm font-bold border border-red-200 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('public.shop.cart.add') }}" method="POST" class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    @csrf
                    <input type="hidden" name="variant_id" :value="selectedVariantId">
                    
                    @if($prodotto->variants->count() > 0)
                        
                        <!-- Selezione Colore -->
                        <template x-if="availableColors().length > 0">
                            <div class="mb-6">
                                <h3 class="text-sm text-gray-900 font-medium whitespace-nowrap mb-3" id="color-choice-heading">Colore: <span x-text="selectedColor"></span></h3>
                                <div class="flex items-center space-x-3">
                                    <template x-for="color in availableColors()" :key="color">
                                        <button type="button" 
                                                @click="selectColor(color)"
                                                class="px-4 py-2 border rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
                                                :class="selectedColor === color ? 'bg-primary text-white border-transparent shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                            <span x-text="color"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Selezione Taglia -->
                        <template x-if="availableSizes().length > 0">
                            <div class="mb-6">
                                <h3 class="text-sm text-gray-900 font-medium whitespace-nowrap mb-3" id="size-choice-heading">Taglia: <span x-text="selectedSize"></span></h3>
                                <div class="grid grid-cols-4 gap-3 sm:grid-cols-6 lg:grid-cols-4">
                                    <template x-for="size in availableSizes()" :key="size">
                                        <button type="button" 
                                                @click="selectSize(size)"
                                                class="px-4 py-2 border rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                                :class="selectedSize === size ? 'bg-primary text-white border-transparent shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'">
                                            <span x-text="size"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Messaggio Mancanza Varianti -->
                        <div x-show="selectedVariantId === null && (selectedColor !== null || selectedSize !== null)" class="mt-2 text-sm text-red-600 mb-4 h-5">
                            La combinazione selezionata non è disponibile.
                        </div>

                        <!-- Quantità -->
                        <div class="mb-6 flex items-center mt-6">
                            <h3 class="text-sm text-gray-900 font-medium mr-4">Quantità:</h3>
                            <input type="number" name="quantita" min="1" :max="currentVariant ? currentVariant.quantita : 1" x-model="quantity" class="w-20 text-center border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            
                            <!-- Check Disponibilità stock -->
                            <template x-if="currentVariant">
                                <span class="ml-4 text-sm" :class="(infiniteStock || (currentVariant && currentVariant.quantita > 0)) ? 'text-green-600' : 'text-red-600'">
                                    <template x-if="infiniteStock">
                                        <span>Disponibile</span>
                                    </template>
                                    <template x-if="!infiniteStock">
                                        <span>
                                            <span x-show="currentVariant && currentVariant.quantita > 0">
                                                Disponibile (<span x-text="currentVariant.quantita"></span>)
                                            </span>
                                            <span x-show="!currentVariant || currentVariant.quantita <= 0">Esaurito</span>
                                        </span>
                                    </template>
                                </span>
                            </template>
                        </div>

                    @else
                        <div class="text-yellow-600 bg-yellow-50 p-4 rounded border border-yellow-200">
                            Prodotto non disponibile al momento.
                        </div>
                    @endif

                    <div class="mt-6 flex">
                        <button type="submit" 
                                :disabled="selectedVariantId === null || (!infiniteStock && currentVariant && currentVariant.quantita <= 0)"
                                class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-8 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-text="(infiniteStock || (currentVariant && currentVariant.quantita > 0)) ? 'Aggiungi al Carrello' : 'Prodotto Esaurito'"></span>
                        </button>
                    </div>
                </form>
            </div>
            
            @if($prodotto->tags->count() > 0)
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-900">Tag Profilazione</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($prodotto->tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-primary">
                                {{ $tag->nome }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productB2C', (variantsRaw, infiniteStock = false) => ({
            variants: variantsRaw,
            infiniteStock: infiniteStock,
            selectedColor: null,
            selectedSize: null,
            selectedVariantId: null,
            quantity: 1,

            init() {
                // Auto-seleziona se ci sono varianti disponibili
                if (this.availableColors().length > 0) {
                    this.selectedColor = this.availableColors()[0];
                }
                if (this.availableSizes().length > 0) {
                    this.selectedSize = this.availableSizes()[0];
                }
                this.doUpdateVariant();
                
                // Watch per agganciare l'immagine della variante se l'utente clicca una thumb
                this.$watch('selectedVariantId', (val) => {
                    const variant = this.currentVariant;
                    if (variant && variant.foto && window.productSwiper) {
                        // Cerca l'indice della foto della variante
                        const thumbs = Array.from(document.querySelectorAll('.product-thumb-btn img'));
                        const idx = thumbs.findIndex(img => img.getAttribute('src').includes(variant.foto) || variant.foto.includes(img.getAttribute('src')));
                        if (idx !== -1) {
                            window.productSwiper.slideTo(idx);
                        }
                    }
                });
            },

            updateVariant() {
                // Ensure array data types for proper method usages by updating available colors / sizes first (fixed AlpineJS bug from the old getter issue)
            },
            
            availableColors() {
                const colors = new Set();
                this.variants.forEach(v => {
                    if(v.colore) colors.add(v.colore);
                });
                return Array.from(colors);
            },

            availableSizes() {
                const sizes = new Set();
                this.variants.forEach(v => {
                    if (this.selectedColor) {
                        if (v.colore === this.selectedColor && v.taglia) sizes.add(v.taglia);
                    } else {
                        if (v.taglia) sizes.add(v.taglia);
                    }
                });
                return Array.from(sizes);
            },

            selectColor(col) {
                this.selectedColor = col;
                if (this.selectedSize && !this.availableSizes().includes(this.selectedSize)) {
                    this.selectedSize = this.availableSizes().length > 0 ? this.availableSizes()[0] : null;
                }
                this.doUpdateVariant();
            },

            selectSize(sz) {
                this.selectedSize = sz;
                this.doUpdateVariant();
            },

            doUpdateVariant() {
                let match = this.variants.find(v => {
                    let cMatch = !this.selectedColor || v.colore === this.selectedColor;
                    let sMatch = !this.selectedSize || v.taglia === this.selectedSize;
                    return cMatch && sMatch;
                });
                
                // Fallback: se nessuna selezione ma ci sono varianti generiche
                if (!match && !this.selectedColor && !this.selectedSize && this.variants.length > 0) {
                    match = this.variants[0];
                }

                if (match) {
                    this.selectedVariantId = match.id;
                } else {
                    this.selectedVariantId = null;
                }
            },

            get currentVariant() {
                if (this.selectedVariantId) {
                    return this.variants.find(v => v.id === this.selectedVariantId);
                }
                return this.variants.length > 0 ? this.variants[0] : null;
            },

            get formattedPrice() {
                const v = this.currentVariant;
                if (!v) return '€ 0,00';
                
                let baseHtml = '';
                if (parseFloat(v.prezzo_scontato) > 0) {
                    baseHtml = `<span class="text-red-600 block">€ ${parseFloat(v.prezzo_scontato).toLocaleString('it-IT', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                <span class="text-lg line-through text-gray-400 block font-medium mt-1">€ ${parseFloat(v.prezzo).toLocaleString('it-IT', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                } else {
                    baseHtml = `<span>€ ${parseFloat(v.prezzo).toLocaleString('it-IT', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                }
                return baseHtml;
            }
        }));
    });
</script>
@endpush
@once
    @push('scripts')
        <script type="module">
            import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs'
            
            document.addEventListener('DOMContentLoaded', () => {
                const mainSwiper = new Swiper('.product-main-swiper', {
                    loop: false,
                    grabCursor: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    on: {
                        init: function() {
                            updateActiveThumb(this.activeIndex);
                        },
                        slideChange: function() {
                            updateActiveThumb(this.activeIndex);
                        }
                    }
                });

                window.productSwiper = mainSwiper;

                function updateActiveThumb(index) {
                    document.querySelectorAll('.product-thumb-btn').forEach((btn) => {
                        const btnIdx = parseInt(btn.getAttribute('data-index'));
                        if (btnIdx === index) {
                            btn.classList.add('border-indigo-500');
                            btn.classList.remove('border-transparent');
                        } else {
                            btn.classList.remove('border-indigo-500');
                            btn.classList.add('border-transparent');
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce
@endsection
