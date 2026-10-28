@extends('public.layouts.main')

@section('title', 'Checkout Sicuro - ' . config('app.name'))
@section('content')
@php
    $shopSection = \App\Models\Section::where('modulo', 'shop')->first();
    $bannerImmagine = $shopSection ? $shopSection->immagine : null;
    $loggedCustomer = auth()->check() ? \App\Models\Customer::where('email', auth()->user()->email)->first() : null;
@endphp

<div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-bg-top bg-fixed px-6" style="background-image: url('{{ $bannerImmagine ? asset($bannerImmagine) : '' }}');">
    <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg">
        <nav class="flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900 font-medium">Home</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <a href="{{ route('public.shop.index') }}" class="hover:text-gray-900 font-medium">Catalogo</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            <span class="text-gray-900 font-medium">Checkout</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-6 xl:mx-auto bg-gray-50 pb-24" x-data="checkoutForm()">
    <div class="w-full pt-0 py-6 px-4 sm:px-6 lg:px-8 bg-white border border-gray-100 shadow-sm rounded-b-lg mb-3 border-t-0">
        <div>
            <h1 class="text-2xl sm:text-3xl">Checkout Sicuro</h1>
        </div>
        <div>
            <a href="{{ route('public.shop.cart.index') }}" class="text-secondary hover:text-primary font-semibold text-sm">&larr; Torna al Carrello</a>
        </div>
    </div>

    <div class="w-full px-0 pt-0">
        <!-- Barra di Ricerca e Filtri Sempre Visibile -->
        @include('public.shop.partials.search_bar')

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
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Come vuoi procedere?</h2>
                    <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                        <div class="flex items-center">
                            <input id="mode-guest" name="mode" type="radio" value="guest" x-model="checkoutMode" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300">
                            <label for="mode-guest" class="ml-3 block text-sm font-medium text-gray-700">
                                Continua come Ospite
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="mode-register" name="mode" type="radio" value="register" x-model="checkoutMode" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300">
                            <label for="mode-register" class="ml-3 block text-sm font-medium text-gray-700">
                                Crea un Account
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Sei già registrato? <a href="{{ route('login', ['redirect_to' => route('public.shop.cart.checkout')]) }}" class="font-medium text-primary hover:text-primary">Accedi qui</a>.
                        </p>
                    </div>
                </div>
                @endguest

                <!-- Form Principale Checkout -->
                <form action="{{ route('public.shop.cart.process') }}" method="POST" class="space-y-8" id="checkout-form" @invalid.capture="showUserData = true">
                    @csrf
                    
                    <input type="hidden" name="checkout_mode" :value="checkoutMode">
                    
                    <!-- 1. I tuoi dati -->
                    <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                        @auth
                        <div class="flex items-center justify-between cursor-pointer border-b pb-4 mb-4 select-none" @click="showUserData = !showUserData">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-xl font-medium text-gray-900">I tuoi dati</h2>
                                    <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded font-normal">Account connesso</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1" x-show="!showUserData">
                                    {{ Auth::user()->name }} &bull; {{ Auth::user()->email }}
                                    <span class="text-primary font-medium ml-2 underline hover:text-secondary">Modifica dati</span>
                                </p>
                            </div>
                            <button type="button" class="text-gray-500 hover:text-gray-700 flex items-center gap-1 text-sm font-medium p-1">
                                <span class="hidden sm:inline" x-text="showUserData ? 'Chiudi' : 'Modifica'"></span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" :class="showUserData ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        @else
                        <h2 class="text-xl font-medium text-gray-900 mb-6 border-b pb-4">I tuoi dati</h2>
                        @endauth
                        
                        <div x-show="showUserData" x-collapse class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <!-- Anagrafica Base -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome *</label>
                                <div class="mt-1">
                                    <input type="text" id="nome" name="nome" value="{{ old('nome', Auth::user()?->name ? explode(' ', Auth::user()->name)[0] : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="cognome" class="block text-sm font-medium text-gray-700">Cognome *</label>
                                <div class="mt-1">
                                    <input type="text" id="cognome" name="cognome" value="{{ old('cognome', Auth::user()?->name ? (count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : '') : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Indirizzo Email *</label>
                                <div class="mt-1">
                                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()?->email) }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm" @if(Auth::check()) readonly @endif>
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
                                            <input type="password" id="password" name="password" x-bind:required="checkoutMode === 'register'" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Conferma Password *</label>
                                        <div class="mt-1">
                                            <input type="password" id="password_confirmation" name="password_confirmation" x-bind:required="checkoutMode === 'register'" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div class="sm:col-span-2">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Telefono / Cellulare *</label>
                                <div class="mt-1">
                                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $loggedCustomer?->telefono ?? $loggedCustomer?->cellulare ?? '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Modalità di Ricezione Ordine -->
                    <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                        <h2 class="text-xl font-medium text-gray-900 mb-6 border-b pb-4">Modalità di Ricezione Ordine</h2>
                        
                        <!-- Dati fissi di consegna a mano a Barletta passati di default -->
                        <input type="hidden" name="nazione" value="Italia">
                        <input type="hidden" name="citta" value="Barletta">
                        <input type="hidden" name="cap" value="76121">
                        <input type="hidden" name="provincia" value="BT">

                        <!-- Scelta tra Ritiro in Sede e Consegna a Domicilio -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-2.5">Come preferisci ricevere il tuo ordine? *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <!-- Opzione 1: Consegna a domicilio -->
                                <label 
                                    :class="tipoConsegna === 'domicilio' 
                                        ? 'border-secondary bg-red-50/50 ring-2 ring-secondary ring-opacity-40 shadow-sm' 
                                        : 'border-gray-200 bg-white hover:border-gray-300 shadow-sm'"
                                    class="relative flex items-start p-4 border rounded-xl cursor-pointer transition-all">
                                    <div class="flex items-center h-5">
                                        <input type="radio" 
                                               name="tipo_consegna" 
                                               value="domicilio" 
                                               x-model="tipoConsegna" 
                                               class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                                🚚 Consegna a Domicilio
                                            </span>
                                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-200">
                                                Barletta
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Consegna direttamente al tuo indirizzo a Barletta all'orario concordato.</p>
                                    </div>
                                </label>

                                <!-- Opzione 2: Ritiro in sede -->
                                <label 
                                    :class="tipoConsegna === 'ritiro' 
                                        ? 'border-secondary bg-red-50/50 ring-2 ring-secondary ring-opacity-40 shadow-sm' 
                                        : 'border-gray-200 bg-white hover:border-gray-300 shadow-sm'"
                                    class="relative flex items-start p-4 border rounded-xl cursor-pointer transition-all">
                                    <div class="flex items-center h-5">
                                        <input type="radio" 
                                               name="tipo_consegna" 
                                               value="ritiro" 
                                               x-model="tipoConsegna" 
                                               class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                                🏪 Ritiro in Sede
                                            </span>
                                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Gratuito
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Passa tu stesso in gastronomia all'orario che indichi senza costi aggiuntivi.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- SEZIONE CONSEGNA A DOMICILIO -->
                        <div x-show="tipoConsegna === 'domicilio'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="mb-5 bg-amber-50 border border-amber-200 rounded-md p-3.5 text-sm text-amber-900 flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <p class="font-medium text-amber-950">Consegna a domicilio</p>
                                    <p class="text-amber-800 text-xs mt-0.5">Disponibile esclusivamente per la città di <strong>Barletta (BT) - CAP 76121</strong>.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <!-- Indirizzo a Barletta -->
                                <div class="sm:col-span-2">
                                    <label for="indirizzo" class="block text-sm font-medium text-gray-700">Indirizzo e N. Civico (a Barletta) *</label>
                                    <div class="mt-1">
                                        <input type="text" 
                                               id="indirizzo" 
                                               name="indirizzo" 
                                               value="{{ old('indirizzo', $loggedCustomer?->indirizzo ?? '') }}" 
                                               :required="tipoConsegna === 'domicilio'"
                                               :disabled="tipoConsegna !== 'domicilio'"
                                               placeholder="Es. Via Roma, 10" 
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                    </div>
                                </div>

                                <!-- Selezione Giorno di Consegna -->
                                <div class="sm:col-span-2">
                                    <label for="data_consegna" class="block text-sm font-medium text-gray-700">Giorno di Consegna *</label>
                                    <div class="mt-1">
                                        <select id="data_consegna" 
                                                name="data_consegna" 
                                                x-model="selectedDate" 
                                                @change="onDateChange()" 
                                                :required="tipoConsegna === 'domicilio'"
                                                :disabled="tipoConsegna !== 'domicilio'"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm font-medium text-gray-800 bg-white">
                                            <option value="" disabled>-- Seleziona un giorno di consegna --</option>
                                            @foreach($availableDates as $dateItem)
                                                <option value="{{ $dateItem['value'] }}">
                                                    {{ $dateItem['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(empty($availableDates))
                                        <p class="text-xs text-red-600 mt-1">Nessuna data di consegna attualmente configurata. Contatta l'amministrazione.</p>
                                    @endif
                                </div>

                                <!-- Selezione Orario di Consegna -->
                                <div class="sm:col-span-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Orario di Consegna *</label>
                                        <span class="text-xs text-gray-500" x-show="selectedDate === todayDate">
                                            Orario attuale: <strong x-text="serverCurrentTime"></strong>
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-show="selectedDate">
                                        <template x-for="slot in slots" :key="slot.id">
                                            <label :class="{
                                                'opacity-60 bg-gray-100 border-gray-200 cursor-not-allowed': !isSlotAvailable(slot),
                                                'border-secondary bg-red-50/70 ring-2 ring-secondary ring-opacity-30 cursor-pointer shadow-sm': isSlotAvailable(slot) && selectedSlot === slot.orario_consegna,
                                                'border-gray-200 bg-white hover:border-gray-300 cursor-pointer shadow-sm': isSlotAvailable(slot) && selectedSlot !== slot.orario_consegna
                                            }" class="relative flex items-center justify-between p-4 border rounded-xl transition-all">
                                                
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" 
                                                           name="orario_consegna" 
                                                           :value="slot.orario_consegna"
                                                           :disabled="tipoConsegna !== 'domicilio' || !isSlotAvailable(slot)"
                                                           x-model="selectedSlot"
                                                           :required="tipoConsegna === 'domicilio'"
                                                           class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 disabled:opacity-40">
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-gray-900 text-base" x-text="'Ore ' + slot.orario_consegna"></span>
                                                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                                                                  :class="slot.periodo === 'pomeriggio' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-amber-50 text-amber-800 border border-amber-100'"
                                                                  x-text="slot.periodo === 'pomeriggio' ? '🌙 Pomeriggio' : '☀️ Mattina'">
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-0.5" x-text="'Accetta ordini entro le ore ' + slot.orario_limite"></p>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-end gap-1.5">
                                                    <!-- Costo Consegna Slot -->
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded"
                                                          :class="parseFloat(slot.costo) > 0 ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'"
                                                          x-text="parseFloat(slot.costo) > 0 ? ('+ € ' + formatPrice(slot.costo)) : 'Gratis'">
                                                    </span>

                                                    <template x-if="!isSlotAvailable(slot)">
                                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-200 text-gray-600 border border-gray-300">
                                                            Non disponibile
                                                        </span>
                                                    </template>

                                                    <template x-if="isSlotAvailable(slot) && selectedSlot === slot.orario_consegna">
                                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-secondary text-white">
                                                            Selezionato
                                                        </span>
                                                    </template>
                                                </div>
                                            </label>
                                        </template>
                                    </div>

                                    <div x-show="!selectedDate" class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-sm text-gray-500 text-center">
                                        Seleziona prima il giorno di consegna per visualizzare gli orari disponibili.
                                    </div>

                                    <!-- Avviso se per la data selezionata non ci sono orari disponibili -->
                                    <div x-show="selectedDate && isAllSlotsDisabled()" class="mt-3 p-3.5 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900 flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Tutti gli orari di consegna per la data selezionata sono scaduti per superamento dell'orario limite. Ti invitiamo a selezionare una data successiva oppure a scegliere il <strong>Ritiro in Sede</strong>.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEZIONE RITIRO IN SEDE -->
                        <div x-show="tipoConsegna === 'ritiro'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <!-- Info Box Ritiro -->
                            <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-950 flex items-start gap-3">
                                <div class="p-2.5 bg-emerald-100 text-emerald-800 rounded-lg shrink-0 mt-0.5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <p class="font-bold text-base text-emerald-950">Gastronomia Salumeria San Nicola</p>
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-200 text-emerald-900 border border-emerald-300">Ritiro Gratuito (€ 0,00)</span>
                                    </div>
                                    <p class="text-emerald-900 text-xs mt-1">
                                        📍 <strong>Punto di Ritiro:</strong> Via Canne 124-12, 76121 Barletta (BT)
                                    </p>
                                    <p class="text-emerald-800 text-xs mt-0.5">
                                        📞 <strong>Telefono / Assistenza:</strong> +39 329 803 5179
                                    </p>
                                    <p class="text-emerald-700 text-xs mt-1.5 font-medium">
                                        Il tuo ordine verrà preparato con cura e tenuto pronto per te all'orario concordato.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <!-- Selezione Giorno di Ritiro -->
                                <div class="sm:col-span-2">
                                    <label for="data_ritiro" class="block text-sm font-medium text-gray-700">Giorno di Ritiro in Sede *</label>
                                    <div class="mt-1">
                                        <select id="data_ritiro" 
                                                name="data_consegna" 
                                                x-model="selectedDate" 
                                                :required="tipoConsegna === 'ritiro'"
                                                :disabled="tipoConsegna !== 'ritiro'"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm font-medium text-gray-800 bg-white">
                                            <option value="" disabled>-- Seleziona un giorno di ritiro --</option>
                                            @foreach($availableDates as $dateItem)
                                                <option value="{{ $dateItem['value'] }}">
                                                    {{ $dateItem['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Selezione Orario di Ritiro -->
                                <div class="sm:col-span-2">
                                    <label for="orario_ritiro" class="block text-sm font-medium text-gray-700">Orario Indicativo di Ritiro in Sede *</label>
                                    <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-3">
                                        <input type="time" 
                                               id="orario_ritiro" 
                                               name="orario_ritiro" 
                                               x-model="orarioRitiro" 
                                               :required="tipoConsegna === 'ritiro'"
                                               :disabled="tipoConsegna !== 'ritiro'"
                                               class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm font-bold text-gray-800 text-lg py-2">
                                        <span class="text-xs text-gray-500">Specifica l'ora approssimativa in cui verrai a ritirare l'ordine.</span>
                                    </div>

                                    <!-- Suggerimenti orari veloci -->
                                    <div class="mt-3">
                                        <span class="text-xs font-medium text-gray-500 block mb-1.5">Orari consigliati veloci:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            <template x-for="timeSug in ['11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '19:00', '19:30', '20:00', '20:30', '21:00']" :key="timeSug">
                                                <button type="button" 
                                                        @click="orarioRitiro = timeSug" 
                                                        :class="orarioRitiro === timeSug 
                                                            ? 'bg-secondary text-white border-secondary font-bold shadow-sm' 
                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-200'"
                                                        class="px-2.5 py-1 text-xs rounded-md border transition-colors"
                                                        x-text="timeSug">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NOTE ORDINE (in comune ad entrambi) -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label for="note_ordine" class="block text-sm font-medium text-gray-700" 
                                   x-text="tipoConsegna === 'ritiro' ? 'Note per il Ritiro (Opzionale)' : 'Note per la Consegna (Opzionale)'">
                            </label>
                            <div class="mt-1">
                                <textarea id="note_ordine" 
                                          name="note_ordine" 
                                          rows="3" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm placeholder-gray-400" 
                                          :placeholder="tipoConsegna === 'ritiro' ? 'Istruzioni particolari per il ritiro in sede (es. passa un familiare, ecc.)...' : 'Istruzioni particolari per la consegna a mano (es. citofono, scala)...'">{{ old('note_ordine') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Richiedi Fattura Aziendale -->
                    <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                        <h2 class="text-xl font-medium text-gray-900 mb-6 border-b pb-4">Richiedi Fattura Aziendale</h2>
                        
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_azienda" name="is_azienda" type="checkbox" value="1" x-model="isAzienda" class="focus:ring-secondary h-4 w-4 text-primary border-gray-300 rounded cursor-pointer">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_azienda" class="font-bold text-gray-700 cursor-pointer">Richiedi Fattura Aziendale</label>
                                <p class="text-gray-500">Spunta questa casella se acquisti per conto di un'azienda o professionista con partita IVA.</p>
                            </div>
                        </div>

                        <!-- Dati Azienda -->
                        <template x-if="isAzienda">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 bg-gray-50 p-5 rounded-md border border-gray-200 mt-6">
                                <div class="sm:col-span-2">
                                    <label for="ragione_sociale" class="block text-sm font-medium text-gray-700">Ragione Sociale *</label>
                                    <div class="mt-1">
                                        <input type="text" id="ragione_sociale" name="ragione_sociale" value="{{ old('ragione_sociale') }}" x-bind:required="isAzienda" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label for="partita_iva" class="block text-sm font-medium text-gray-700">Partita IVA *</label>
                                    <div class="mt-1">
                                        <input type="text" id="partita_iva" name="partita_iva" value="{{ old('partita_iva') }}" x-bind:required="isAzienda" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label for="codice_fiscale" class="block text-sm font-medium text-gray-700">Codice Fiscale (Opzionale)</label>
                                    <div class="mt-1">
                                        <input type="text" id="codice_fiscale" name="codice_fiscale" value="{{ old('codice_fiscale') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label for="sdi" class="block text-sm font-medium text-gray-700">Codice SDI</label>
                                    <div class="mt-1">
                                        <input type="text" id="sdi" name="sdi" value="{{ old('sdi') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Es. KRRH6B9">
                                    </div>
                                </div>
                                <div>
                                    <label for="pec" class="block text-sm font-medium text-gray-700">PEC Aziendale</label>
                                    <div class="mt-1">
                                        <input type="email" id="pec" name="pec" value="{{ old('pec') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- 4. Metodo di Pagamento -->
                    <div class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 p-8">
                         <h2 class="text-xl font-medium text-gray-900 mb-6 border-b pb-4">Metodo di Pagamento</h2>
                         
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
                                 <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-secondary transition-colors cursor-pointer">
                                     <input id="payment_stripe" name="payment_method" form="checkout-form" type="radio" value="stripe" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300 cursor-pointer" required {{ $preferred_method === 'stripe' ? 'checked' : '' }}>
                                     <label for="payment_stripe" class="ml-3 block text-sm font-medium text-gray-900 flex items-center flex-1 cursor-pointer">
                                        Carta di Credito (Stripe)
                                     </label>
                                 </div>
                                 @endif

                                 @if($paypal_enabled)
                                 <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-secondary transition-colors cursor-pointer">
                                     <input id="payment_paypal" name="payment_method" form="checkout-form" type="radio" value="paypal" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300 cursor-pointer" required {{ $preferred_method === 'paypal' ? 'checked' : '' }}>
                                     <label for="payment_paypal" class="ml-3 block text-sm font-medium text-gray-900 flex items-center flex-1 cursor-pointer">
                                        PayPal Checkout
                                     </label>
                                 </div>
                                 @endif

                                 @if($bonifico_enabled)
                                 <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-secondary transition-colors cursor-pointer">
                                     <input id="payment_bonifico" name="payment_method" form="checkout-form" type="radio" value="bonifico" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300 cursor-pointer" required {{ $preferred_method === 'bonifico' ? 'checked' : '' }}>
                                     <label for="payment_bonifico" class="ml-3 block text-sm font-medium text-gray-900 flex-1 cursor-pointer">
                                        Bonifico Bancario Anticipato
                                     </label>
                                 </div>
                                 @endif

                                 @if($contrassegno_enabled)
                                 <div class="border rounded px-4 py-3 flex items-center bg-gray-50 border-gray-200 hover:border-secondary transition-colors cursor-pointer">
                                     <input id="payment_contrassegno" name="payment_method" form="checkout-form" type="radio" value="contrassegno" class="focus:ring-secondary h-4 w-4 text-secondary border-gray-300 cursor-pointer" required {{ $preferred_method === 'contrassegno' ? 'checked' : '' }}>
                                     <label for="payment_contrassegno" class="ml-3 block text-sm font-medium text-gray-900 flex-1 cursor-pointer">
                                        Pagamento alla Consegna
                                     </label>
                                 </div>
                                 @endif
                             </div>
                         @endif
                    </div>
                </form>

            </div>

            <!-- Riepilogo Ordine (Sidebar Destra) -->
            <div class="mt-10 lg:mt-0 lg:col-span-5">
                <section aria-labelledby="summary-heading" class="bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 shadow border-t-4 border-primary sticky top-6">
                    <h2 id="summary-heading" class="text-xl font-medium text-gray-900 mb-6">Il tuo Ordine</h2>

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

                        <!-- Modalità Ricezione -->
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-gray-600">Modalità</dt>
                            <dd class="font-semibold" :class="tipoConsegna === 'ritiro' ? 'text-emerald-700' : 'text-slate-800'">
                                <span x-show="tipoConsegna === 'domicilio'">🚚 A Domicilio</span>
                                <span x-show="tipoConsegna === 'ritiro'">🏪 Ritiro in Sede</span>
                            </dd>
                        </div>

                        <!-- Giorno -->
                        <div class="flex items-center justify-between text-sm" x-show="selectedDate">
                            <dt class="text-gray-600" x-text="tipoConsegna === 'ritiro' ? 'Giorno Ritiro' : 'Giorno Consegna'"></dt>
                            <dd class="font-medium text-gray-900" x-text="getSelectedDateLabel()"></dd>
                        </div>

                        <!-- Orario -->
                        <div class="flex items-center justify-between text-sm" x-show="tipoConsegna === 'domicilio' ? selectedSlot : orarioRitiro">
                            <dt class="text-gray-600" x-text="tipoConsegna === 'ritiro' ? 'Orario Ritiro' : 'Orario Consegna'"></dt>
                            <dd class="font-semibold text-secondary" x-text="'Ore ' + (tipoConsegna === 'domicilio' ? selectedSlot : orarioRitiro)"></dd>
                        </div>

                        <!-- Costo Consegna / Ritiro -->
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-gray-600" x-text="tipoConsegna === 'ritiro' ? 'Spese Ritiro' : 'Costo Consegna'"></dt>
                            <dd class="font-semibold" :class="(tipoConsegna === 'domicilio' && deliveryCost > 0) ? 'text-amber-900' : 'text-emerald-700'" 
                                x-text="(tipoConsegna === 'domicilio' && deliveryCost > 0) ? ('+ € ' + formatPrice(deliveryCost)) : 'Gratuito'">
                            </dd>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Totale Da Pagare</dt>
                            <dd class="text-2xl font-bold text-primary">€ <span x-text="formatPrice(totalPrice)"></span></dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <button type="submit" form="checkout-form" :disabled="isSubmitDisabled()" :class="{'opacity-50 cursor-not-allowed': isSubmitDisabled()}" class="w-full btn font-semibold flex justify-center items-center rounded-md">
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
            showUserData: {{ (Auth::check() && !empty($loggedCustomer?->telefono)) ? 'false' : 'true' }},
            subtotal: {{ $totale }},
            
            tipoConsegna: '{{ old("tipo_consegna", "domicilio") }}',
            orarioRitiro: '{{ old("orario_ritiro", "12:30") }}',

            serverCurrentTime: '{{ now()->format("H:i") }}',
            todayDate: '{{ now()->format("Y-m-d") }}',
            selectedDate: '{{ old("data_consegna", !empty($availableDates) ? $availableDates[0]["value"] : "") }}',
            selectedSlot: '{{ old("orario_consegna", "") }}',
            slots: @json($deliverySlots),
            availableDates: @json($availableDates),

            init() {
                this.ensureValidSlot();
            },

            getSelectedDateLabel() {
                const found = this.availableDates.find(d => d.value === this.selectedDate);
                return found ? found.label : this.selectedDate;
            },

            get deliveryCost() {
                if (this.tipoConsegna === 'ritiro') return 0;
                if (!this.selectedSlot) return 0;
                const currentSlotObj = this.slots.find(s => s.orario_consegna === this.selectedSlot);
                return (currentSlotObj && this.isSlotAvailable(currentSlotObj)) ? parseFloat(currentSlotObj.costo || 0) : 0;
            },

            get totalPrice() {
                return this.subtotal + this.deliveryCost;
            },

            isSlotAvailable(slot) {
                if (!slot || !slot.attivo) return false;
                const dateObj = this.availableDates.find(d => d.value === this.selectedDate);
                if (!dateObj) return false;

                // Controlla se la fascia mattina / pomeriggio è attiva per il giorno selezionato
                const period = (slot.periodo || 'mattina').toLowerCase();
                if (period === 'mattina' && !dateObj.mattina) return false;
                if (period === 'pomeriggio' && !dateObj.pomeriggio) return false;

                // Se la data è oggi, controlla che l'ora attuale sia prima dell'orario limite
                if (this.selectedDate === this.todayDate) {
                    return this.serverCurrentTime < slot.orario_limite;
                }
                return true;
            },

            isAllSlotsDisabled() {
                if (!this.slots || this.slots.length === 0) return true;
                return this.slots.every(s => !this.isSlotAvailable(s));
            },

            isSubmitDisabled() {
                if (!this.selectedDate) return true;
                if (this.tipoConsegna === 'domicilio') {
                    return this.isAllSlotsDisabled() || !this.selectedSlot;
                }
                if (this.tipoConsegna === 'ritiro') {
                    return !this.orarioRitiro;
                }
                return false;
            },

            onDateChange() {
                this.ensureValidSlot();
            },

            ensureValidSlot() {
                // Se lo slot attualmente selezionato non è disponibile per la nuova data, deselezionalo
                if (this.selectedSlot) {
                    const currentSlotObj = this.slots.find(s => s.orario_consegna === this.selectedSlot);
                    if (!currentSlotObj || !this.isSlotAvailable(currentSlotObj)) {
                        this.selectedSlot = '';
                    }
                }
                // Se non c'è slot selezionato, prova a preselezionare il primo disponibile
                if (!this.selectedSlot) {
                    const firstAvailable = this.slots.find(s => this.isSlotAvailable(s));
                    if (firstAvailable) {
                        this.selectedSlot = firstAvailable.orario_consegna;
                    }
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
