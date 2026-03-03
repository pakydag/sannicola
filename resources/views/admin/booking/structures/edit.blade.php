<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica Struttura: {{ $structure->nome }}</h2>
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
                    </div>

                    <form action="{{ route('admin.booking.structures.update', $structure) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- TAB: GENERALE -->
                        <div x-show="tab === 'generale'" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Nome Struttura *</label>
                                    <input type="text" name="nome" value="{{ old('nome', $structure->nome) }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Numero Bagni</label>
                                    <input type="number" name="bagni" value="{{ old('bagni', $structure->bagni) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Numero Camere da Letto</label>
                                    <input type="number" name="camere_letto" value="{{ old('camere_letto', $structure->camere_letto) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Posti Letto Totali</label>
                                    <input type="number" name="posti_totali" value="{{ old('posti_totali', $structure->posti_totali) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Costo al Giorno (€)</label>
                                    <input type="number" step="0.01" name="costo_al_giorno" value="{{ old('costo_al_giorno', $structure->costo_al_giorno) }}" min="0" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="attivo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('attivo', $structure->attivo) ? 'checked' : '' }}>
                                    <span class="ml-2 font-bold text-gray-700">Attiva e Prenotabile</span>
                                </label>
                            </div>
                        </div>

                        <!-- TAB: DESCRIZIONE -->
                        <div x-show="tab === 'descrizione'" class="space-y-4" style="display:none;">
                            <label class="block text-gray-700 font-bold mb-2">Descrizione della Struttura</label>
                            <textarea name="descrizione" id="descrizione" class="w-full">{{ old('descrizione', $structure->descrizione) }}</textarea>
                        </div>

                        <!-- TAB: FOTO -->
                        <div x-show="tab === 'foto'" class="space-y-4" style="display:none;" x-data="{ photos: {{ json_encode($structure->photos->pluck('path')) }} }">
                            <label class="block text-gray-700 font-bold mb-2">Galleria Immagini</label>
                            <button type="button" @click="photos.push('')" class="mb-4 bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-3 rounded text-sm">+ Aggiungi Immagine</button>
                            <template x-for="(foto, index) in photos" :key="index">
                                <div class="flex items-center mb-2 gap-2">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="photos[index]" name="photos[]" class="shadow border rounded w-full py-2 px-3 text-gray-700 readonly-foto" placeholder="URL Immagine" readonly>
                                            <button type="button" @click="window.openFmPhoto(index)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded font-bold">Sfoglia</button>
                                            <button type="button" @click="photos.splice(index, 1)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-bold">X</button>
                                        </div>
                                        <div class="mt-2" x-show="photos[index]">
                                            <img :src="photos[index]" class="h-20 w-32 object-cover rounded shadow-sm border">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded text-lg">Salva Modifiche</button>
                            <a href="{{ route('admin.booking.structures.index') }}" class="py-3 px-4 text-gray-500 hover:text-gray-800 font-bold">Annulla</a>
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

            // File Manager logic per le foto
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
