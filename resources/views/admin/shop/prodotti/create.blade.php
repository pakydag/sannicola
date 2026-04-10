<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crea Nuovo Prodotto B2B</h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'generale' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <!-- Nav Tabs -->
                    <div class="border-b border-gray-200 mb-6 flex overflow-x-auto">
                        <button type="button" @click="tab = 'generale'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'generale', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'generale' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Generale</button>
                        <button type="button" @click="tab = 'descrizione'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'descrizione', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'descrizione' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Descrizione</button>
                        <button type="button" @click="tab = 'foto'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'foto', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'foto' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Galleria Foto</button>
                        <button type="button" @click="tab = 'varianti'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'varianti', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'varianti' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Varianti B2B (Misure/Colori)</button>
                    </div>

                    <form action="{{ route('admin.shop.prodotti.store') }}" method="POST">
                        @csrf
                        
                        <!-- TAB: GENERALE -->
                        <div x-show="tab === 'generale'" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Nome Prodotto *</label>
                                    <input type="text" name="nome" value="{{ old('nome') }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">URL Slug</label>
                                    <input type="text" name="slug" value="{{ old('slug') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="Auto-generato">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">SKU Base (Padre)</label>
                                    <input type="text" name="sku_padre" value="{{ old('sku_padre') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Marca</label>
                                    <input type="text" name="marca" value="{{ old('marca') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Categoria</label>
                                    <select name="shop_category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                        <option value="">Nessuna</option>
                                        @foreach($categorie->whereNull('parent_id') as $macro)
                                            <optgroup label="{{ $macro->nome }}">
                                                @php
                                                    $cats = $categorie->where('parent_id', $macro->id);
                                                @endphp
                                                @foreach($cats as $cat)
                                                    <option value="{{ $cat->id }}" {{ old('shop_category_id') == $cat->id ? 'selected' : '' }} class="font-bold">
                                                        {{ $cat->nome }}
                                                    </option>
                                                    @php
                                                        $subs = $categorie->where('parent_id', $cat->id);
                                                    @endphp
                                                    @foreach($subs as $sub)
                                                        <option value="{{ $sub->id }}" {{ old('shop_category_id') == $sub->id ? 'selected' : '' }}>
                                                            &nbsp;&nbsp;&nbsp;{{ $sub->nome }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Collezione</label>
                                    <select name="shop_collection_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                        <option value="">Nessuna</option>
                                        @foreach($collezioni as $c)
                                            <option value="{{ $c->id }}" {{ old('shop_collection_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="mt-4 mb-2">
                                <label class="block text-gray-700 font-bold mb-2">Tag Esistenti</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 border border-gray-200 p-3 rounded bg-gray-50 h-32 overflow-y-auto">
                                    @forelse($tags as $tag)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                                            <span class="ml-2 text-gray-700 text-sm">{{ $tag->nome }}</span>
                                        </label>
                                    @empty
                                        <span class="text-sm text-gray-500">Nessun tag. Puoi crearne uno inserendolo nel campo qui sotto.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Aggiungi Nuovi Tag</label>
                                <input type="text" name="tags_string" value="{{ old('tags_string') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="Inserisci i nuovi tag separati da virgola (es. Estate, Sottocosto)">
                            </div>
                            <div class="mt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('visibile', true) ? 'checked' : '' }}>
                                    <span class="ml-2 font-bold text-gray-700">Visibile nel Catalogo B2B</span>
                                </label>
                            </div>
                        </div>

                        <!-- TAB: DESCRIZIONE -->
                        <div x-show="tab === 'descrizione'" class="space-y-4" style="display:none;">
                            <label class="block text-gray-700 font-bold mb-2">Descrizione Completa</label>
                            <textarea name="descrizione" id="descrizione" class="w-full">{{ old('descrizione') }}</textarea>
                        </div>

                        <!-- TAB: FOTO -->
                        <div x-show="tab === 'foto'" class="space-y-4" style="display:none;" x-data="{ photos: [] }">
                            <label class="block text-gray-700 font-bold mb-2">Galleria Immagini</label>
                            <button type="button" @click="photos.push('')" class="mb-4 bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-3 rounded text-sm">+ Aggiungi Immagine</button>
                            <template x-for="(foto, index) in photos" :key="index">
                                <div class="flex items-center mb-2 gap-2">
                                    <input type="text" x-model="photos[index]" :name="`foto_aggiuntive[]`" class="shadow border rounded w-full py-2 px-3 text-gray-700 readonly-foto" placeholder="URL Immagine">
                                    <button type="button" @click="window.openFmPhoto(index)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded font-bold">Sfoglia</button>
                                    <button type="button" @click="photos.splice(index, 1)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-bold">X</button>
                                </div>
                            </template>
                        </div>

                        <!-- TAB: VARIANTI -->
                        <div x-show="tab === 'varianti'" class="space-y-4" style="display:none;" x-data="{ variants: [{sku:'', ean:'', colore:'', taglia:'', prezzo:'0.00', prezzo_scontato:'', quantita: 0, foto:''}] }">
                            <label class="block text-gray-700 font-bold mb-2">Combinazioni, Prezzi e Magazzino</label>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border font-bold text-left">SKU</th>
                                            <th class="p-2 border font-bold text-left">EAN</th>
                                            <th class="p-2 border font-bold text-left">Colore</th>
                                            <th class="p-2 border font-bold text-left">Taglia</th>
                                            <th class="p-2 border font-bold text-right" style="width:100px;">Prezzo €</th>
                                            <th class="p-2 border font-bold text-right" style="width:100px;">Sconto €</th>
                                            <th class="p-2 border font-bold text-right" style="width:80px;">Q.tà</th>
                                            <th class="p-2 border w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(v, index) in variants" :key="index">
                                            <tr>
                                                <td class="p-1 border"><input type="text" :name="`variants[${index}][sku]`" x-model="v.sku" class="w-full text-xs p-1 border border-gray-300 rounded"></td>
                                                <td class="p-1 border"><input type="text" :name="`variants[${index}][ean]`" x-model="v.ean" class="w-full text-xs p-1 border border-gray-300 rounded"></td>
                                                <td class="p-1 border"><input type="text" :name="`variants[${index}][colore]`" x-model="v.colore" class="w-full text-xs p-1 border border-gray-300 rounded"></td>
                                                <td class="p-1 border"><input type="text" :name="`variants[${index}][taglia]`" x-model="v.taglia" class="w-full text-xs p-1 border border-gray-300 rounded" placeholder="M, 42..."></td>
                                                <td class="p-1 border"><input type="number" step="0.01" :name="`variants[${index}][prezzo]`" x-model="v.prezzo" required class="w-full text-xs p-1 border border-gray-300 rounded text-right"></td>
                                                <td class="p-1 border"><input type="number" step="0.01" :name="`variants[${index}][prezzo_scontato]`" x-model="v.prezzo_scontato" class="w-full text-xs p-1 border border-gray-300 rounded text-right"></td>
                                                <td class="p-1 border"><input type="number" step="1" :name="`variants[${index}][quantita]`" x-model="v.quantita" class="w-full text-xs p-1 border border-gray-300 rounded text-right"></td>
                                                <td class="p-1 border text-center text-red-600 cursor-pointer font-bold" @click="variants.splice(index, 1)">X</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" @click="variants.push({sku:'', ean:'', colore:'', taglia:'', prezzo:'0.00', prezzo_scontato:'', quantita:0, foto:''})" class="mt-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 py-1 px-3 rounded text-sm font-bold">+ Aggiungi Variante</button>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded text-lg">Salva Prodotto</button>
                            <a href="{{ route('admin.shop.prodotti.index') }}" class="py-3 px-4 text-gray-500 hover:text-gray-800 font-bold">Annulla</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            // CKEditor
            CKEDITOR.replace('descrizione');

            // File Manager logic per le foto (array dinamico di alpine)
            let currentFmIndex = null;
            window.openFmPhoto = function(index) {
                currentFmIndex = index;
                window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
            };
            function fmSetLink($url) {
                if (currentFmIndex !== null) {
                    let inputs = document.querySelectorAll('.readonly-foto');
                    if(inputs[currentFmIndex]) {
                        inputs[currentFmIndex].value = $url;
                        inputs[currentFmIndex].dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    currentFmIndex = null;
                }
            }
        </script>
    @endpush
</x-app-layout>
