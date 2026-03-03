@extends('public.layouts.main')

@section('title', 'Il Tuo Carrello - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
            <span>/</span>
            <a href="{{ route('public.shop.index') }}" class="hover:text-gray-900">Shop</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Carrello</span>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Il Tuo Carrello</h1>

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
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                
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
                <section aria-labelledby="summary-heading" class="mt-16 bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4 shadow">
                    <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Riepilogo Ordine</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotale Prodotti</dt>
                            <dd class="text-sm font-medium text-gray-900">€ {{ number_format($totale, 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-sm text-gray-600">Spedizione stimata</dt>
                            <dd class="text-sm font-medium text-gray-900">€ 5,00</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Totale Ordine</dt>
                            <dd class="text-xl font-bold text-gray-900">€ {{ number_format($totale + 5, 2, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <a href="{{ route('public.shop.cart.checkout') }}" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-50 flex justify-center items-center">
                            Procedi al Checkout
                        </a>
                    </div>
                </section>
            </div>
        @endif
        
    </div>
</div>
@endsection
