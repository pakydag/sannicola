@extends('public.layouts.main')

@section('title', 'Checkout Sicuro - ' . config('app.name'))
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
            <span class="text-gray-900 font-medium">Checkout</span>
        </nav>
    </div>
</div>

<div class="bg-gray-50 pb-24" x-data="checkoutForm()">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-12 border-t-0 flex items-center justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Checkout Sicuro</h1>
        </div>
        <div>
            <a href="{{ route('public.shop.cart.index') }}" class="text-secondary hover:text-primary font-semibold text-sm">&larr; Torna al Carrello</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Ci sono stati dei problemi con la tua richiesta:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            
            <div class="lg:col-span-7 space-y-8">
                
                @guest
                <!-- Scelta Modalità Checkout per Utenti non Autenticati -->
                <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Come vuoi procedere?</h2>
                    <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                        <div class="flex items-center">
                            <input id="mode-guest" name="mode" type="radio" value="guest" x-model="checkoutMode" class="focus:ring-secondary h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="mode-guest" class="ml-3 block text-sm font-medium text-gray-700">
                                Continua come Ospite
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="mode-register" name="mode" type="radio" value="register" x-model="checkoutMode" class="focus:ring-secondary h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="mode-register" class="ml-3 block text-sm font-medium text-gray-700">
                                Crea un Account
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Sei già registrato? <a href="{{ route('login', ['redirect_to' => route('public.shop.cart.checkout')]) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Accedi qui</a>.
                        </p>
                    </div>
                </div>
                @endguest

                <!-- Form Principale Checkout -->
                <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Dati di Spedizione e Fatturazione</h2>
                    
                    <form action="{{ route('public.shop.cart.process') }}" method="POST" class="space-y-6" id="checkout-form">
                        @csrf
                        
                        <input type="hidden" name="checkout_mode" :value="checkoutMode">
                        
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <!-- Anagrafica Base -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome *</label>
                                <div class="mt-1">
                                    <input type="text" id="nome" name="nome" value="{{ old('nome', Auth::user()?->name ? explode(' ', Auth::user()->name)[0] : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="cognome" class="block text-sm font-medium text-gray-700">Cognome *</label>
                                <div class="mt-1">
                                    <input type="text" id="cognome" name="cognome" value="{{ old('cognome', Auth::user()?->name ? (count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : '') : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Indirizzo Email *</label>
                                <div class="mt-1">
                                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()?->email) }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" @if(Auth::check()) readonly @endif>
                                </div>
                            </div>

                            <!-- Password Fields if Registering -->
                            <template x-if="checkoutMode === 'register'">
                                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                                    <div class="sm:col-span-2">
                                        <p class="text-sm font-bold text-gray-700 mb-2">Crea la tua Password</p>
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                                        <div class="mt-1">
                                            <input type="password" id="password" name="password" x-bind:required="checkoutMode === 'register'" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Conferma Password *</label>
                                        <div class="mt-1">
                                            <input type="password" id="password_confirmation" name="password_confirmation" x-bind:required="checkoutMode === 'register'" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div class="sm:col-span-2">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Telefono / Cellulare *</label>
                                <div class="mt-1">
                                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="sm:col-span-2 mt-4">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="is_azienda" name="is_azienda" type="checkbox" value="1" x-model="isAzienda" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_azienda" class="font-bold text-gray-700">Richiedi Fattura Aziendale</label>
                                        <p class="text-gray-500">Spunta questa casella se acquisti per conto di un'azienda o professionista con partita IVA.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dati Azienda -->
                            <template x-if="isAzienda">
                                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 bg-indigo-50 p-4 rounded-md border border-indigo-100 mt-4">
                                    <div class="sm:col-span-2">
                                        <label for="ragione_sociale" class="block text-sm font-medium text-gray-700">Ragione Sociale *</label>
                                        <div class="mt-1">
                                            <input type="text" id="ragione_sociale" name="ragione_sociale" value="{{ old('ragione_sociale') }}" x-bind:required="isAzienda" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="partita_iva" class="block text-sm font-medium text-gray-700">Partita IVA *</label>
                                        <div class="mt-1">
                                            <input type="text" id="partita_iva" name="partita_iva" value="{{ old('partita_iva') }}" x-bind:required="isAzienda" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="codice_fiscale" class="block text-sm font-medium text-gray-700">Codice Fiscale (Opzionale)</label>
                                        <div class="mt-1">
                                            <input type="text" id="codice_fiscale" name="codice_fiscale" value="{{ old('codice_fiscale') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="sdi" class="block text-sm font-medium text-gray-700">Codice SDI</label>
                                        <div class="mt-1">
                                            <input type="text" id="sdi" name="sdi" value="{{ old('sdi') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Es. KRRH6B9">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="pec" class="block text-sm font-medium text-gray-700">PEC Aziendale</label>
                                        <div class="mt-1">
                                            <input type="email" id="pec" name="pec" value="{{ old('pec') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="sm:col-span-2 pt-4 border-t border-gray-200 mt-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Indirizzo di Spedizione</h3>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="nazione" class="block text-sm font-medium text-gray-700">Nazione *</label>
                                <div class="mt-1">
                                    <input type="text" id="nazione" name="nazione" x-model="nazione" readonly class="block w-full bg-gray-50 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none sm:text-sm cursor-not-allowed">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="indirizzo" class="block text-sm font-medium text-gray-700">Indirizzo e N. Civico *</label>
                                <div class="mt-1">
                                    <input type="text" id="indirizzo" name="indirizzo" value="{{ old('indirizzo') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="citta" class="block text-sm font-medium text-gray-700">Città *</label>
                                <div class="mt-1">
                                    <input type="text" id="citta" name="citta" value="{{ old('citta') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="cap" class="block text-sm font-medium text-gray-700">CAP *</label>
                                <div class="mt-1">
                                    <input type="text" id="cap" name="cap" value="{{ old('cap') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia (Sigla es. MI, RM)</label>
                                <div class="mt-1">
                                    <input type="text" id="provincia" name="provincia" value="{{ old('provincia') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="note_ordine" class="block text-sm font-medium text-gray-700">Note Ordine (Opzionale)</label>
                                <div class="mt-1">
                                    <textarea id="note_ordine" name="note_ordine" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm placeholder-gray-400" placeholder="Istruzioni particolari per la consegna...">{{ old('note_ordine') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                 <!-- Metodo di Pagamento -->
                <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                     <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Metodo di Pagamento</h2>
                     
                     @php
                        $stripe_enabled = \App\Models\Setting::where('key', 'payment_stripe_enabled')->value('value') == '1';
                        $bonifico_enabled = \App\Models\Setting::where('key', 'payment_bonifico_enabled')->value('value') == '1';
                        $contrassegno_enabled = \App\Models\Setting::where('key', 'payment_contrassegno_enabled')->value('value') == '1';
                        $paypal_enabled = \App\Models\Setting::where('key', 'payment_paypal_enabled')->value('value') == '1';

                        $preferred_method = '';
                        if (auth()->check()) {
                            $preferred_method = \App\Models\Customer::where('email', auth()->user()->email)->value('metodo_pagamento_preferito');
                        }
                     @endphp

                     @if(!$stripe_enabled && !$bonifico_enabled && !$contrassegno_enabled && !$paypal_enabled)
                        <div class="p-4 bg-yellow-50 text-yellow-800 rounded border border-yellow-200">
                            Attualmente non ci sono metodi di pagamento disponibili online. Contatta l'assistenza per completare l'ordine.
                        </div>
                     @else
                         <div class="space-y-4">
                             @if($stripe_enabled)
                             <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-indigo-200 transition-colors cursor-pointer">
                                 <input id="payment_stripe" name="payment_method" form="checkout-form" type="radio" value="stripe" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 cursor-pointer" required {{ $preferred_method === 'stripe' ? 'checked' : '' }}>
                                 <label for="payment_stripe" class="ml-3 block text-sm font-medium text-gray-900 flex items-center flex-1 cursor-pointer">
                                    Carta di Credito (Stripe)
                                 </label>
                             </div>
                             @endif

                             @if($paypal_enabled)
                             <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-indigo-200 transition-colors cursor-pointer">
                                 <input id="payment_paypal" name="payment_method" form="checkout-form" type="radio" value="paypal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 cursor-pointer" required {{ $preferred_method === 'paypal' ? 'checked' : '' }}>
                                 <label for="payment_paypal" class="ml-3 block text-sm font-medium text-gray-900 flex items-center flex-1 cursor-pointer">
                                    PayPal Checkout
                                 </label>
                             </div>
                             @endif

                             @if($bonifico_enabled)
                             <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-indigo-200 transition-colors cursor-pointer">
                                 <input id="payment_bonifico" name="payment_method" form="checkout-form" type="radio" value="bonifico" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 cursor-pointer" required {{ $preferred_method === 'bonifico' ? 'checked' : '' }}>
                                 <label for="payment_bonifico" class="ml-3 block text-sm font-medium text-gray-900 flex-1 cursor-pointer">
                                    Bonifico Bancario Anticipato
                                 </label>
                             </div>
                             @endif

                             @if($contrassegno_enabled)
                             <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-indigo-200 transition-colors cursor-pointer">
                                 <input id="payment_contrassegno" name="payment_method" form="checkout-form" type="radio" value="contrassegno" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 cursor-pointer" required {{ $preferred_method === 'contrassegno' ? 'checked' : '' }}>
                                 <label for="payment_contrassegno" class="ml-3 block text-sm font-medium text-gray-900 flex-1 cursor-pointer">
                                    Contrassegno (Pagamento alla Consegna)
                                 </label>
                             </div>
                             @endif
                         </div>
                     @endif
                </div>

            </div>

            <!-- Riepilogo Ordine (Sidebar Destra) -->
            <div class="mt-10 lg:mt-0 lg:col-span-5">
                <section aria-labelledby="summary-heading" class="bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 shadow border-t-4 border-indigo-600 sticky top-6">
                    <h2 id="summary-heading" class="text-xl font-bold text-gray-900 mb-6">Il tuo Ordine</h2>

                    <ul role="list" class="divide-y divide-gray-200">
                        @php $totale = 0; @endphp
                        @foreach($cart as $variant_id => $item)
                        @php 
                            $subtotale = $item['prezzo'] * $item['quantita'];
                            $totale += $subtotale;
                        @endphp
                        <li class="py-4 flex">
                            @if($item['foto'])
                                <img src="{{ Str::startsWith($item['foto'], 'http') ? $item['foto'] : url('/') . $item['foto'] }}" class="h-16 w-16 rounded border object-cover">
                            @else
                                <div class="h-16 w-16 rounded bg-gray-100 flex items-center justify-center border">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="ml-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between text-sm font-medium text-gray-900">
                                        <h4>{{ $item['prodotto_nome'] }}</h4>
                                        <p class="ml-4">€ {{ number_format($subtotale, 2, ',', '.') }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if($item['colore']){{ $item['colore'] }}@endif 
                                        @if($item['colore'] && $item['taglia']) / @endif 
                                        @if($item['taglia']){{ $item['taglia'] }}@endif
                                    </p>
                                </div>
                                <div class="flex flex-1 items-end justify-between text-sm">
                                    <p class="text-gray-500">Q.tà {{ $item['quantita'] }}</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <dl class="mt-6 space-y-4 border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-gray-600">Subtotale</dt>
                            <dd class="font-medium text-gray-900">€ <span x-text="formatPrice(subtotal)"></span></dd>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-gray-600">Spedizione</dt>
                            <dd class="font-medium text-gray-900 group relative">
                                <span x-show="shippingCost > 0">€ <span x-text="formatPrice(shippingCost)"></span></span>
                                <span x-show="shippingCost == 0" class="text-emerald-600 font-bold uppercase tracking-wider text-xs">Gratis</span>
                                
                                <template x-if="freeThreshold > 0 && subtotal < freeThreshold">
                                    <div class="absolute -top-10 right-0 bg-slate-800 text-white text-[10px] px-2 py-1 rounded shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                        Gratis con altri € <span x-text="formatPrice(freeThreshold - subtotal)"></span>
                                    </div>
                                </template>
                            </dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Totale Da Pagare</dt>
                            <dd class="text-2xl font-extrabold text-indigo-600">€ <span x-text="formatPrice(parseFloat(subtotal) + parseFloat(shippingCost))"></span></dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <button type="submit" form="checkout-form" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-4 px-4 text-lg font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-50 flex justify-center items-center h-14">
                            Genera Ordine
                        </button>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkoutForm() {
        return {
            checkoutMode: '{{ Auth::check() ? 'login' : 'register' }}',
            isAzienda: {{ old('is_azienda') ? 'true' : 'false' }},
            nazione: '{{ old('nazione', 'Italia') }}',
            subtotal: {{ $totale }},
            shippingCost: 0,
            freeThreshold: {{ $freeThreshold }},
            costs: @json($shippingCosts->pluck('costo', 'nazione')),
            
            init() {
                this.updateShipping();
            },
            
            updateShipping() {
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
