<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica Cliente B2B: {{ $cliente->nome }} {{ $cliente->cognome }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.shop.clienti.update', $cliente) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Section: Dati Personali -->
                            <div class="p-4 bg-gray-50 rounded border">
                                <h3 class="font-bold text-lg mb-4 border-b pb-2">Dati di Accesso e Contatto</h3>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Nome *</label>
                                    <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Cognome *</label>
                                    <input type="text" name="cognome" value="{{ old('cognome', $cliente->cognome) }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Email (Login) *</label>
                                    <input type="email" name="email" value="{{ old('email', $cliente->email) }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Nuova Password <span class="text-sm font-normal text-gray-500">(lascia vuoto per non modificare)</span></label>
                                    <input type="password" name="password" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Telefono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Cellulare</label>
                                    <input type="text" name="cellulare" value="{{ old('cellulare', $cliente->cellulare) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                            </div>
                            
                            <!-- Section: Dati Aziendali / Fatturazione -->
                            <div class="p-4 bg-gray-50 rounded border">
                                <h3 class="font-bold text-lg mb-4 border-b pb-2">Dati Aziendali e Fatturazione</h3>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Ragione Sociale</label>
                                    <input type="text" name="ragione_sociale" value="{{ old('ragione_sociale', $cliente->ragione_sociale) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Partita IVA</label>
                                    <input type="text" name="partita_iva" value="{{ old('partita_iva', $cliente->partita_iva) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Codice Fiscale</label>
                                    <input type="text" name="codice_fiscale" value="{{ old('codice_fiscale', $cliente->codice_fiscale) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Codice Destinatario (SDI)</label>
                                    <input type="text" name="sdi" value="{{ old('sdi', $cliente->sdi) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">PEC</label>
                                    <input type="email" name="pec" value="{{ old('pec', $cliente->pec) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Indirizzo Sede Logistica / Fatturazione</label>
                                    <input type="text" name="indirizzo" value="{{ old('indirizzo', $cliente->indirizzo) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Comune</label>
                                        <input type="text" name="citta" value="{{ old('citta', $cliente->citta) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">CAP</label>
                                        <input type="text" name="cap" value="{{ old('cap', $cliente->cap) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Provincia</label>
                                        <input type="text" name="provincia" value="{{ old('provincia', $cliente->provincia) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Nazione</label>
                                        <input type="text" name="nazione" value="{{ old('nazione', $cliente->nazione) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="attivo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('attivo', $cliente->attivo) ? 'checked' : '' }}>
                                <span class="ml-2 font-bold text-gray-700">Account Cliente Attivo (può accedere)</span>
                            </label>
                        </div>

                        <div class="mt-8 pt-6 border-t flex justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded text-lg">Aggiorna Cliente</button>
                            <a href="{{ route('admin.shop.clienti.index') }}" class="py-3 px-4 text-gray-500 font-bold hover:text-gray-800">Annulla</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
