<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuovo Prodotto B2B & Varianti') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ variants: [{size: '', color: '', quantity: 0}] }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.b2b.products.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Dati Base -->
                    <div class="lg:col-span-1 border-r pr-6 space-y-4">
                        <div class="bg-white p-6 shadow sm:rounded-lg">
                            <h3 class="text-md font-bold mb-4 border-b pb-2">Dati Generali</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome Prodotto *</label>
                                    <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Marchio *</label>
                                    <select name="b2b_brand_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Seleziona...</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stagione</label>
                                    <input type="text" name="season" placeholder="es. PE 2024" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prezzo B2B (€) *</label>
                                    <input type="number" step="0.01" name="price" placeholder="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="has_stock" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">Gestisci Magazzino (Giacenze)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Varianti Matrix -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 shadow sm:rounded-lg">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h3 class="text-md font-bold">Varianti (Taglie & Colori)</h3>
                                <button type="button" @click="variants.push({size: '', color: '', quantity: 0})" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md border">+ Aggiungi Riga</button>
                            </div>

                            <div class="space-y-2">
                                <div class="grid grid-cols-12 gap-2 text-xs font-bold text-gray-500 uppercase px-2">
                                    <div class="col-span-4">Colore</div>
                                    <div class="col-span-4">Taglia</div>
                                    <div class="col-span-3">Q.tà</div>
                                    <div class="col-span-1"></div>
                                </div>

                                <template x-for="(variant, index) in variants" :key="index">
                                    <div class="grid grid-cols-12 gap-2 items-center bg-gray-50 p-2 rounded-md border border-gray-100">
                                        <div class="col-span-4">
                                            <input type="text" :name="'variants['+index+'][color]'" x-model="variant.color" placeholder="es. Nero" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="col-span-4">
                                            <input type="text" :name="'variants['+index+'][size]'" x-model="variant.size" placeholder="es. XL o 42" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="col-span-3">
                                            <input type="number" :name="'variants['+index+'][quantity]'" x-model="variant.quantity" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="col-span-1 text-center">
                                            <button type="button" @click="variants.splice(index, 1)" class="text-red-400 hover:text-red-600">×</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.b2b.products.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Annulla</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Salva Prodotto e Inventario
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
