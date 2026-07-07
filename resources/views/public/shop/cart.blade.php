@extends('public.layouts.main')

@section('title', 'Il Tuo Carrello - ' . config('app.name'))

@section('content')
@php
    $totale = 0;
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
            <span class="text-gray-900 font-medium">Carrello</span>
        </nav>
    </div>
</div>

<div class="bg-gray-50 pb-24">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-12 border-t-0">
        <div class="text-center titoli">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900">
                Il Tuo Carrello
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-100 text-green-700 px-4 py-3 rounded text-sm font-bold border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 rounded text-sm font-bold border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Il tuo carrello è vuoto</h3>
                <p class="mt-1 text-sm text-gray-500">Aggiungi dei prodotti per poter procedere all'acquisto.</p>
                <div class="mt-6">
                    <a href="{{ route('public.shop.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Inizia lo Shopping
                    </a>
                </div>
            </div>
        @else
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start" x-data="cartSummary()">
                
                <div class="lg:col-span-8">
                    <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200 bg-white shadow rounded-lg px-6">
                        @php $totale = 0; @endphp
                        @foreach($cart as $variant_id => $item)
                            @php 
                                $subtotale = $item['prezzo'] * $item['quantita'];
                                $totale += $subtotale;
                            @endphp
                            <li class="flex py-6 sm:py-10">
                                <div class="flex-shrink-0">
                                    @if($item['foto'])
                                        <img src="{{ Str::startsWith($item['foto'], 'http') ? $item['foto'] : url('/') . $item['foto'] }}" class="w-24 h-24 rounded-md object-cover object-center sm:w-32 sm:h-32 border">
                                    @else
                                        <div class="w-24 h-24 rounded-md bg-gray-100 sm:w-32 sm:h-32 flex items-center justify-center border">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                    <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                        <div>
                                            <div class="flex justify-between">
                                                <h3 class="text-sm">
                                                    <a href="#" class="font-medium text-gray-700 hover:text-gray-800">
                                                        {{ $item['prodotto_nome'] }}
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="mt-1 flex text-sm">
                                                @if($item['colore'])
                                                    <p class="text-gray-500">Colore: {{ $item['colore'] }}</p>
                                                @endif
                                                @if($item['taglia'])
                                                    <p class="ml-4 pl-4 border-l border-gray-200 text-gray-500">Taglia: {{ $item['taglia'] }}</p>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm font-medium text-gray-900">€ {{ number_format($item['prezzo'], 2, ',', '.') }} x {{ $item['quantita'] }}</p>
                                        </div>

                                        <div class="mt-4 sm:mt-0 sm:pr-9">
                                            <label for="quantity-{{ $variant_id }}" class="sr-only">Quantità, {{ $item['prodotto_nome'] }}</label>
                                            <p class="text-sm font-bold text-gray-900">Subtotale: € {{ number_format($subtotale, 2, ',', '.') }}</p>

                                            <div class="absolute top-0 right-0">
                                                <form action="{{ route('public.shop.cart.remove', $variant_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500">
                                                        <span class="sr-only">Rimuovi</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Disponibile per la spedizione</span>
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Riepilogo Ordine -->
                <section aria-labelledby="summary-heading" class="mt-16 bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4 shadow border-t-2 border-indigo-500 sticky top-6">
                    <h2 id="summary-heading" class="text-xl font-bold text-gray-900 border-b pb-4 mb-6">Riepilogo Ordine</h2>

                    <div class="mb-6">
                        <label for="estimate_nation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Destinazione per la stima</label>
                        <input type="text" id="estimate_nation" x-model="nazione" readonly class="w-full bg-slate-100 border-slate-200 rounded-lg text-sm text-gray-600 focus:outline-none p-3 cursor-not-allowed">
                    </div>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotale Prodotti</dt>
                            <dd class="text-sm font-bold text-gray-900">€ <span x-text="formatPrice(subtotal)"></span></dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4 group relative">
                            <dt class="text-sm text-gray-600 flex items-center">
                                Spedizione stimata
                                <svg class="w-3 h-3 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </dt>
                            <dd class="text-sm font-bold text-gray-900">
                                <span x-show="shippingCost > 0">€ <span x-text="formatPrice(shippingCost)"></span></span>
                                <span x-show="shippingCost == 0" class="text-emerald-600 uppercase tracking-widest text-xs">Gratis</span>
                            </dd>

                            <template x-if="freeThreshold > 0 && subtotal < freeThreshold">
                                <div class="absolute -top-10 right-0 bg-slate-800 text-white text-[10px] px-2 py-2 rounded shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all z-20">
                                    Aggiungi € <span x-text="formatPrice(freeThreshold - subtotal)"></span> per la spedizione <span class="text-emerald-400 font-bold">Gratis</span>!
                                </div>
                            </template>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Totale Ordine</dt>
                            <dd class="text-2xl font-black text-indigo-600">€ <span x-text="formatPrice(parseFloat(subtotal) + parseFloat(shippingCost))"></span></dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <a href="{{ route('public.shop.cart.checkout') }}" class="w-full bg-indigo-600 border border-transparent rounded-xl shadow-lg py-4 px-4 text-lg font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all flex justify-center items-center h-14 uppercase tracking-widest">
                            Procedi al Checkout
                        </a>
                    </div>

                    @if($freeThreshold > 0)
                    <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            <span>Soglia Gratuita</span>
                            <span>€ <span x-text="formatPrice(freeThreshold)"></span></span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 mb-1 overflow-hidden">
                            <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-700" :style="'width: ' + Math.min((subtotal / freeThreshold) * 100, 100) + '%'"></div>
                        </div>
                    </div>
                    @endif
                </section>
            </div>
        @endif
        
    </div>
</div>
@push('scripts')
<script>
    function cartSummary() {
        return {
            nazione: 'Italia',
            subtotal: {{ $totale }},
            shippingCost: 0,
            freeThreshold: {{ $freeThreshold }},
            costs: @json($shippingCosts->pluck('costo', 'nazione')),
            
            init() {
                this.updateShipping();
            },
            
            updateShipping() {
                if (!this.nazione) {
                    this.shippingCost = 0;
                    return;
                }
                let cost = this.costs[this.nazione] !== undefined ? parseFloat(this.costs[this.nazione]) : 5.00;
                
                if (this.freeThreshold > 0 && this.subtotal >= this.freeThreshold) {
                    this.shippingCost = 0;
                } else {
                    this.shippingCost = cost;
                }
            },
            
            formatPrice(price) {
                return parseFloat(price).toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }
</script>
@endpush
@endsection
