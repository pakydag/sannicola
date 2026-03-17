<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Modifica Prodotto B2B</h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.b2b.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Col -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome Prodotto</label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Marchio</label>
                                    <select name="b2b_brand_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ $product->b2b_brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stagione</label>
                                    <input type="text" name="season" value="{{ old('season', $product->season) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prezzo B2B (€) *</label>
                                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descrizione</label>
                                    <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Right Col: Variants Management -->
                            <div class="space-y-4" x-data="{ 
                                variants: {{ json_encode($product->variants) }},
                                addVariant() {
                                    this.variants.push({ id: '', size: '', color: '', quantity: 0 });
                                },
                                removeVariant(index) {
                                    this.variants.splice(index, 1);
                                }
                            }">
                                <label class="block text-sm font-medium text-gray-700">Varianti (Taglia/Colore/Q.tà)</label>
                                <div class="space-y-2">
                                    <template x-for="(variant, index) in variants" :key="index">
                                        <div class="flex gap-2 items-center">
                                            <input type="text" :name="'variants['+index+'][size]'" x-model="variant.size" placeholder="Taglia" class="w-1/4 border-gray-300 rounded-md shadow-sm text-sm">
                                            <input type="text" :name="'variants['+index+'][color]'" x-model="variant.color" placeholder="Colore" class="w-1/4 border-gray-300 rounded-md shadow-sm text-sm">
                                            <input type="number" :name="'variants['+index+'][quantity]'" x-model="variant.quantity" placeholder="Q.tà" class="w-1/4 border-gray-300 rounded-md shadow-sm text-sm">
                                            <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-700 font-bold">×</button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="addVariant()" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    + Aggiungi Variante
                                </button>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t flex justify-end">
                            <a href="{{ route('admin.b2b.products.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 px-4 py-2">Annulla</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md shadow-sm">
                                Aggiorna Prodotto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
