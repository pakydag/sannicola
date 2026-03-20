@extends('public.layouts.main')

@section('title', 'Dati Personali - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 pb-24" x-data="{ isAzienda: {{ old('is_azienda', $customer->ragione_sociale ? '1' : '0') }} == '1' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Area Clienti</h1>
            <p class="mt-2 text-sm text-gray-500">Modifica i tuoi dati personali, l'indirizzo di spedizione e le preferenze di fatturazione.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <ul class="list-disc pl-5 space-y-1 text-sm text-red-700 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-8">
            
            <!-- Sidebar Nav Area Clienti -->
            <aside class="lg:col-span-3 mb-8 lg:mb-0">
                <nav class="space-y-1 bg-white p-4 rounded-lg shadow">
                    <a href="{{ route('public.account.dashboard') }}" class="text-gray-900 hover:text-gray-900 hover:bg-gray-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        I Miei Ordini
                    </a>
                    <a href="{{ route('public.account.profile') }}" class="bg-indigo-50 text-indigo-700 hover:text-indigo-700 hover:bg-indigo-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-indigo-500 group-hover:text-indigo-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Dati Personali
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-200">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-900 hover:bg-red-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <svg class="text-red-400 group-hover:text-red-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Esci
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- Main Content: Profilo Editor -->
            <div class="lg:col-span-9">
                <form action="{{ route('public.account.updateProfile') }}" method="POST" class="bg-white shadow rounded-lg px-4 py-6 sm:p-6 lg:p-8 space-y-6">
                    @csrf
                    
                    <h2 class="text-xl font-bold text-gray-900 border-b border-gray-200 pb-4 mb-6">Dati Profilo e Spedizione</h2>
                    
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                        
                        <!-- Dati Base -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome *</label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $customer->nome ?? (explode(' ', $user->name)[0] ?? '')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="cognome" class="block text-sm font-medium text-gray-700">Cognome *</label>
                            <input type="text" id="cognome" name="cognome" value="{{ old('cognome', $customer->cognome ?? (count(explode(' ', $user->name))>1 ? explode(' ', $user->name)[1] : '')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Indirizzo Email</label>
                            <input type="email" id="email" value="{{ $user->email }}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 text-gray-500 rounded-md shadow-sm sm:text-sm" title="L'email di accesso non può essere modificata">
                            <p class="mt-1 text-xs text-gray-500">L'email di registrazione/accesso non è modificabile a fini di sicurezza.</p>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Telefono / Cellulare *</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $customer->telefono) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Indirizzo -->
                        <div class="sm:col-span-2 mt-4">
                            <label for="nazione" class="block text-sm font-medium text-gray-700">Nazione *</label>
                            <select id="nazione" name="nazione" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Italia" {{ old('nazione', $customer->nazione) == 'Italia' ? 'selected' : '' }}>Italia</option>
                                <option value="San Marino" {{ old('nazione', $customer->nazione) == 'San Marino' ? 'selected' : '' }}>San Marino</option>
                                <option value="Svizzera" {{ old('nazione', $customer->nazione) == 'Svizzera' ? 'selected' : '' }}>Svizzera</option>
                                <option value="Francia" {{ old('nazione', $customer->nazione) == 'Francia' ? 'selected' : '' }}>Francia</option>
                                <option value="Germania" {{ old('nazione', $customer->nazione) == 'Germania' ? 'selected' : '' }}>Germania</option>
                                <option value="Spagna" {{ old('nazione', $customer->nazione) == 'Spagna' ? 'selected' : '' }}>Spagna</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="indirizzo" class="block text-sm font-medium text-gray-700">Indirizzo e N. Civico *</label>
                            <input type="text" id="indirizzo" name="indirizzo" value="{{ old('indirizzo', $customer->indirizzo) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="citta" class="block text-sm font-medium text-gray-700">Città *</label>
                            <input type="text" id="citta" name="citta" value="{{ old('citta', $customer->citta) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="cap" class="block text-sm font-medium text-gray-700">CAP *</label>
                            <input type="text" id="cap" name="cap" value="{{ old('cap', $customer->cap) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <!-- Fatturazione Opzionale -->
                        <div class="sm:col-span-2 mt-6">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_azienda" name="is_azienda" type="checkbox" value="1" x-model="isAzienda" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_azienda" class="font-bold text-gray-700">Dati Aziendali per Fatturazione</label>
                                    <p class="text-gray-500">Spunta questa casella se acquisti tramite Partita IVA come Azienda/Professionista.</p>
                                </div>
                            </div>
                        </div>

                        <template x-if="isAzienda">
                            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 bg-indigo-50 p-4 rounded-md border border-indigo-100">
                                <div class="sm:col-span-2">
                                    <label for="ragione_sociale" class="block text-sm font-medium text-gray-700">Ragione Sociale *</label>
                                    <input type="text" id="ragione_sociale" name="ragione_sociale" value="{{ old('ragione_sociale', $customer->ragione_sociale) }}" x-bind:required="isAzienda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="partita_iva" class="block text-sm font-medium text-gray-700">Partita IVA *</label>
                                    <input type="text" id="partita_iva" name="partita_iva" value="{{ old('partita_iva', $customer->partita_iva) }}" x-bind:required="isAzienda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="codice_fiscale" class="block text-sm font-medium text-gray-700">Codice Fiscale (Opzionale)</label>
                                    <input type="text" id="codice_fiscale" name="codice_fiscale" value="{{ old('codice_fiscale', $customer->codice_fiscale) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="sdi" class="block text-sm font-medium text-gray-700">Codice SDI</label>
                                    <input type="text" id="sdi" name="sdi" value="{{ old('sdi', $customer->sdi) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm placeholder-gray-400" placeholder="Es. KRRH6B9">
                                </div>
                                <div>
                                    <label for="pec" class="block text-sm font-medium text-gray-700">PEC Aziendale</label>
                                    <input type="email" id="pec" name="pec" value="{{ old('pec', $customer->pec) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </template>
                        
                        <!-- Metodo di Pagamento Preferito -->
                        <div class="sm:col-span-2 mt-6 pt-6 border-t border-gray-100">
                             <label for="metodo_pagamento_preferito" class="block text-sm font-medium text-gray-700">Metodo di Pagamento Preferito</label>
                             <p class="text-xs text-gray-400 mb-2">Verrà pre-selezionato automaticamente durante il checkout.</p>
                             <select id="metodo_pagamento_preferito" name="metodo_pagamento_preferito" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                 <option value="">Nessuna preferenza</option>
                                 <option value="stripe" {{ old('metodo_pagamento_preferito', $customer->metodo_pagamento_preferito) == 'stripe' ? 'selected' : '' }}>Carta di Credito (Stripe)</option>
                                 <option value="paypal" {{ old('metodo_pagamento_preferito', $customer->metodo_pagamento_preferito) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                 <option value="bonifico" {{ old('metodo_pagamento_preferito', $customer->metodo_pagamento_preferito) == 'bonifico' ? 'selected' : '' }}>Bonifico Bancario</option>
                                 <option value="contrassegno" {{ old('metodo_pagamento_preferito', $customer->metodo_pagamento_preferito) == 'contrassegno' ? 'selected' : '' }}>Contrassegno (Contanti)</option>
                             </select>
                        </div>
                        
                    </div>

                    <div class="pt-5 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-6 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Salva Modifiche
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
