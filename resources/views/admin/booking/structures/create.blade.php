<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crea Nuova Struttura</h2>
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
                        <button type="button" @click="tab = 'prezzi'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'prezzi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'prezzi' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Prezzi & Periodi</button>
                        <button type="button" @click="tab = 'foto'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'foto', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'foto' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Galleria Foto</button>
                        <button type="button" @click="tab = 'servizi'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'servizi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'servizi' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Servizi</button>
                        <button type="button" @click="tab = 'extra'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'extra', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'extra' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">Extra</button>
                    </div>

                    <form action="{{ route('admin.booking.structures.store') }}" method="POST">
                        @csrf
                        
                        <!-- TAB: GENERALE -->
                        <div x-show="tab === 'generale'" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Nome Struttura *</label>
                                    <input type="text" name="nome" value="{{ old('nome') }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Numero Bagni</label>
                                    <input type="number" name="bagni" value="{{ old('bagni', 1) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Numero Camere da Letto</label>
                                    <input type="number" name="camere_letto" value="{{ old('camere_letto', 1) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Posti Letto Totali</label>
                                    <input type="number" name="posti_totali" value="{{ old('posti_totali', 1) }}" min="1" required class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="attivo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('attivo', true) ? 'checked' : '' }}>
                                    <span class="ml-2 font-bold text-gray-700">Attiva e Prenotabile</span>
                                </label>
                            </div>
                        </div>

                        <!-- TAB: DESCRIZIONE -->
                        <div x-show="tab === 'descrizione'" class="space-y-4" style="display:none;">
                            <label class="block text-gray-700 font-bold mb-2">Descrizione della Struttura</label>
                            <textarea name="descrizione" id="descrizione" class="w-full">{{ old('descrizione') }}</textarea>
                        </div>

                        <!-- TAB: PREZZI & PERIODI -->
                        <div x-show="tab === 'prezzi'" class="space-y-8" style="display:none;" x-data="{ 
                            tipo_prezzo: '{{ old('tipo_prezzo', 'fisso') }}',
                            varianti: {{ old('varianti') ? json_encode(array_values(old('varianti'))) : '[]' }},
                            prezzi: {{ old('prezzi') ? json_encode(array_values(old('prezzi'))) : '[]' }},
                            addVariant() {
                                const id = 'new_' + Date.now();
                                this.varianti.push({id: id, nome: ''});
                            },
                            removeVariant(index) {
                                if(confirm('Sei sicuro? Rimuovendo la variante verranno rimosse anche le relative fasce di prezzo.')) {
                                    const id = String(this.varianti[index].id);
                                    this.varianti.splice(index, 1);
                                    this.prezzi = this.prezzi.filter(p => String(p.variant_temp_id) !== id);
                                }
                            }
                        }">
                            
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 shadow-sm">
                                <label class="block text-indigo-900 font-extrabold mb-4 uppercase text-xs tracking-widest">Modalità di Calcolo Prezzo</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center p-4 bg-white rounded-xl border-2 transition-all cursor-pointer hover:border-indigo-300" :class="tipo_prezzo === 'fisso' ? 'border-indigo-500 bg-indigo-50/30' : 'border-transparent'">
                                        <input type="radio" name="tipo_prezzo" value="fisso" x-model="tipo_prezzo" class="text-indigo-600 h-5 w-5 focus:ring-indigo-500">
                                        <div class="ml-4">
                                            <span class="block font-extrabold text-indigo-900">1) Costo Fisso per Periodo</span>
                                            <span class="text-xs text-indigo-600">Prezzo basato solo sulle date.</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 bg-white rounded-xl border-2 transition-all cursor-pointer hover:border-indigo-300" :class="tipo_prezzo === 'persona' ? 'border-indigo-500 bg-indigo-50/30' : 'border-transparent'">
                                        <input type="radio" name="tipo_prezzo" value="persona" x-model="tipo_prezzo" class="text-indigo-600 h-5 w-5 focus:ring-indigo-500">
                                        <div class="ml-4">
                                            <span class="block font-extrabold text-indigo-900">2) Costo per Persona per Periodo</span>
                                            <span class="text-xs text-indigo-600">Prezzo basato su varianti (Adulto, Bambino, etc).</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Gestione Varianti (Solo se "per persona") -->
                            <div x-show="tipo_prezzo === 'persona'" x-transition class="space-y-4 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-widest">Configurazione Varianti Ospiti</h3>
                                    <button type="button" @click="addVariant()" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-xl text-xs font-bold shadow-sm flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        Nuova Variante
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <template x-for="(variante, vIdx) in varianti" :key="variante.id">
                                        <div class="flex items-center gap-2 bg-white p-2 rounded-xl border shadow-sm">
                                            <input type="hidden" :name="'varianti['+vIdx+'][id]'" :value="String(variante.id)">
                                            <input type="text" x-model="variante.nome" :name="'varianti['+vIdx+'][nome]'" required placeholder="es. Adulto" class="flex-1 border-0 focus:ring-0 text-sm font-bold p-1">
                                            <button type="button" @click="removeVariant(vIdx)" class="text-red-400 hover:text-red-600 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="varianti.length === 0" class="text-center py-4 bg-white/50 rounded-xl border-2 border-dashed">
                                    <p class="text-xs text-gray-500 italic">Definisci almeno una variante (es. Adulto, Bambino, etc.)</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-widest">Fasce di Prezzo Stagionali</h3>
                                    <button type="button" @click="prezzi.push({variant_temp_id: varianti.length > 0 ? String(varianti[0].id) : '', start_date: '', end_date: '', prezzo: ''})" 
                                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-5 rounded-xl text-xs font-bold shadow-md transition-all flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        Aggiungi Periodo
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="(fascia, index) in prezzi" :key="index">
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm relative group hover:border-indigo-200 transition-colors">
                                            <div x-show="tipo_prezzo === 'persona'">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Variante</label>
                                                <select :name="'prezzi['+index+'][variant_temp_id]'" 
                                                    x-init="$nextTick(() => { $el.value = String(fascia.variant_temp_id || ''); })"
                                                    @change="fascia.variant_temp_id = $el.value"
                                                    class="w-full text-sm border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    <template x-for="v in varianti" :key="v.id">
                                                        <option :value="String(v.id)" x-text="v.nome" :selected="String(v.id) === String(fascia.variant_temp_id)"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div :class="tipo_prezzo === 'fisso' ? 'md:col-span-2' : ''">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Dal giorno (Incluso)</label>
                                                <input type="date" x-model="fascia.start_date" :name="'prezzi['+index+'][start_date]'" required class="w-full text-sm border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div :class="tipo_prezzo === 'fisso' ? 'md:col-span-1' : ''">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Al giorno (Escluso)</label>
                                                <input type="date" x-model="fascia.end_date" :name="'prezzi['+index+'][end_date]'" required class="w-full text-sm border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Prezzo (€)</label>
                                                <input type="number" step="0.01" x-model="fascia.prezzo" :name="'prezzi['+index+'][prezzo]'" required class="w-full text-sm border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-extrabold text-indigo-700" placeholder="0.00">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" @click="prezzi.splice(index, 1)" class="w-full h-[38px] flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition-all border border-red-100">
                                                    Elimina
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="prezzi.length === 0" class="text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                        <p class="text-sm text-gray-400 italic">Nessun periodo stagionale definito. Definisci almeno un periodo per poter prenotare.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: FOTO -->
                        <div x-show="tab === 'foto'" class="space-y-4" style="display:none;" x-data="{ photos: {{ old('photos') ? json_encode(array_values(old('photos'))) : '[]' }} }">
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

                        <!-- TAB: SERVIZI -->
                        <div x-show="tab === 'servizi'" class="space-y-6" style="display:none;">
                            <p class="text-sm text-gray-500 mb-4">Seleziona i servizi disponibili per questa struttura. <a href="{{ route('admin.booking.services.index') }}" class="text-indigo-600 font-bold hover:underline">Gestisci categorie e servizi →</a></p>
                            @if(isset($serviceCategories) && $serviceCategories->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($serviceCategories as $cat)
                                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                            <h4 class="font-extrabold text-gray-900 flex items-center gap-2 mb-4">
                                                <span class="text-xl">{{ $cat->icona }}</span>
                                                {{ $cat->nome }}
                                            </h4>
                                            <div class="space-y-2">
                                                @foreach($cat->services as $service)
                                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-white p-1.5 rounded-lg transition-colors">
                                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ (is_array(old('services')) && in_array($service->id, old('services'))) ? 'checked' : '' }}>
                                                        <span class="text-sm text-gray-700">{{ $service->nome }}</span>
                                                    </label>
                                                @endforeach
                                                @if($cat->services->count() === 0)
                                                    <p class="text-xs text-gray-400 italic">Nessun servizio in questa categoria.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <p class="text-sm text-gray-400 italic">Nessuna categoria di servizi definita. <a href="{{ route('admin.booking.services.index') }}" class="text-indigo-600 font-bold hover:underline">Crea la prima categoria →</a></p>
                                </div>
                            @endif
                        </div>

                        <!-- TAB: EXTRA -->
                        <div x-show="tab === 'extra'" class="space-y-6" style="display:none;">
                            <p class="text-sm text-gray-500 mb-4">Seleziona i servizi extra a pagamento disponibili per questa struttura. <a href="{{ route('admin.booking.extras.index') }}" class="text-indigo-600 font-bold hover:underline">Gestisci servizi extra →</a></p>
                            @if(isset($availableExtras) && $availableExtras->count() > 0)
                                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($availableExtras as $extra)
                                            <label class="flex items-center justify-between gap-2 cursor-pointer bg-white p-4 rounded-xl border border-gray-200 hover:border-indigo-300 transition-all shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <input type="checkbox" name="extras[]" value="{{ $extra->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ (is_array(old('extras')) && in_array($extra->id, old('extras'))) ? 'checked' : '' }}>
                                                    <div>
                                                        <span class="block font-bold text-gray-900">{{ $extra->nome }}</span>
                                                        <span class="text-xs text-gray-500">€{{ number_format($extra->prezzo, 2) }} / giorno</span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <p class="text-sm text-gray-400 italic">Nessun servizio extra definito. <a href="{{ route('admin.booking.extras.index') }}" class="text-indigo-600 font-bold hover:underline">Crea il primo extra →</a></p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded text-lg">Salva Struttura</button>
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
                        let relativeUrl = $url;
                        try {
                            // Estrae il path dall'URL completo
                            let urlObj = new URL($url);
                            relativeUrl = urlObj.pathname;
                            
                            // Pulizia prefissi comuni
                            relativeUrl = relativeUrl.replace('/baseweb/public', '');
                            relativeUrl = relativeUrl.replace('/public', '');
                        } catch(e) {
                            // Se non è un URL valido, lo lasciamo così com'è
                        }

                        // Garantisce che inizi sempre con una barra /
                        if (!relativeUrl.startsWith('/')) {
                            relativeUrl = '/' + relativeUrl;
                        }

                        inputs[currentFmIndex].value = relativeUrl;
                        inputs[currentFmIndex].dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    currentFmIndex = null;
                }
            }
        </script>
    @endpush
</x-app-layout>
