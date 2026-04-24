<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica Articolo') }}: {{ $articolo->titolo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <form action="{{ route('admin.articoli.update', $articolo) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Sezione -->
                        <div class="mb-4">
                            <label for="section_id" class="block text-gray-700 text-sm font-bold mb-2">Sezione *</label>
                            <select name="section_id" id="section_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- Seleziona una Sezione --</option>
                                @foreach($sezioni as $sezione)
                                    <option value="{{ $sezione->id }}" {{ old('section_id', $articolo->section_id) == $sezione->id ? 'selected' : '' }}>
                                        {{ $sezione->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Titolo -->
                        <div class="mb-4">
                            <label for="titolo" class="block text-gray-700 text-sm font-bold mb-2">Titolo *</label>
                            <input type="text" name="titolo" id="titolo" value="{{ old('titolo', $articolo->titolo) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('titolo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Sottotitolo -->
                        <div class="mb-4">
                            <label for="sottotitolo" class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo</label>
                            <input type="text" name="sottotitolo" id="sottotitolo" value="{{ old('sottotitolo', $articolo->sottotitolo) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('sottotitolo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- URL Custom (Slug) -->
                        <div class="mb-4">
                            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">URL Personalizzato (Opzionale)</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $articolo->slug) }}" placeholder="Lascia vuoto per autogenerare (es. 15-it)"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Senza spazi, usa i trattini. Es: il-mio-nuovo-articolo</p>
                            @error('slug') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Visibile (Pubblicato) -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('visibile', $articolo->visibile) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Pubblica questo articolo (visibile a tutti)</span>
                            </label>
                            @error('visibile') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Includi Modulo Contatti -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_contact_form" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('has_contact_form', $articolo->has_contact_form) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Includi modulo di contatto alla fine dell'articolo</span>
                            </label>
                            @error('has_contact_form') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Mostra Data -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="mostra_data" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('mostra_data', $articolo->mostra_data) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Mostra la data di pubblicazione nell'articolo</span>
                            </label>
                            @error('mostra_data') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Descrizione -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="descrizione" class="block text-gray-700 text-sm font-bold">Descrizione *</label>
                                <button type="button" id="fm-button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold py-1 px-3 rounded inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Inserisci Immagine dal File Manager
                                </button>
                            </div>
                            <textarea name="descrizione" id="descrizione" rows="5"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descrizione', $articolo->descrizione) }}</textarea>
                            @error('descrizione') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Link -->
                        <div class="mb-4">
                            <label for="link" class="block text-gray-700 text-sm font-bold mb-2">Link esterno (URL)</label>
                            <input type="url" name="link" id="link" value="{{ old('link', $articolo->link) }}" placeholder="https://..."
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('link') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Foto e Video Principale + Allineamento -->
                        <div class="mb-8 bg-gray-50 p-4 rounded border border-gray-200">
                            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Media Principale Articolo</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto -->
                                <div>
                                    <label for="foto" class="block text-gray-700 text-sm font-bold mb-2">Foto Principale</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="foto" id="foto" value="{{ old('foto', $articolo->foto) }}" readonly placeholder="Scegli foto..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <button type="button" id="fm-foto-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0 text-sm">
                                            Scegli
                                        </button>
                                        <button type="button" id="fm-foto-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if($articolo->hasMedia('foto'))
                                        <div class="mt-2 text-xs text-gray-500">
                                            <img src="{{ $articolo->getFirstMediaUrl('foto', 'thumb') }}" class="h-16 w-16 object-cover rounded border shadow-sm">
                                        </div>
                                    @elseif($articolo->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset($articolo->foto) }}" class="h-16 w-16 object-cover rounded border shadow-sm border-gray-200">
                                        </div>
                                    @endif
                                </div>

                                <!-- Video -->
                                <div>
                                    <label for="video" class="block text-gray-700 text-sm font-bold mb-2">Video Principale (MP4)</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="video" id="video" value="{{ old('video', $articolo->video) }}" readonly placeholder="Scegli video..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <button type="button" id="fm-video-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0 text-sm">
                                            Scegli
                                        </button>
                                        <button type="button" id="fm-video-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if($articolo->video)
                                        <div class="mt-2 text-xs text-gray-500">
                                            <p class="truncate">Video presente: {{ basename($articolo->video) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <label for="allineamento_media" class="block text-gray-700 text-sm font-bold mb-2">Allineamento Media (Foto o Video)</label>
                                <select name="allineamento_media" id="allineamento_media" class="shadow border rounded w-full md:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                    <option value="left" {{ old('allineamento_media', $articolo->allineamento_media) == 'left' ? 'selected' : '' }}>⬅ Sinistra</option>
                                    <option value="center" {{ old('allineamento_media', $articolo->allineamento_media) == 'center' ? 'selected' : '' }}>↔ Centro</option>
                                    <option value="right" {{ old('allineamento_media', $articolo->allineamento_media) == 'right' ? 'selected' : '' }}>➡ Destra</option>
                                </select>
                            </div>
                        </div>

                        <!-- Allegato -->
                        <div class="mb-8">
                            <label for="allegato" class="block text-gray-700 text-sm font-bold mb-2">Sostituisci Allegato (Lascia vuoto per mantenere attuale)</label>
                            <div class="flex items-stretch">
                                <input type="text" name="allegato" id="allegato" value="{{ old('allegato', $articolo->allegato) }}" readonly placeholder="Nessun allegato selezionato. Clicca su Scegli..."
                                    class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                                <button type="button" id="fm-allegato-button" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Scegli file
                                </button>
                                <button type="button" id="fm-allegato-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded shadow" title="Rimuovi">X</button>
                            </div>
                            @if($articolo->hasMedia('allegati'))
                                <div class="mt-2 text-sm text-gray-600">
                                    <p>Allegato attuale: <a href="{{ $articolo->getFirstMediaUrl('allegati') }}" target="_blank" class="text-blue-500 hover:underline">Scarica/Visualizza</a></p>
                                </div>
                            @endif
                            @error('allegato') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        <!-- ======== Pannello SEO & Condivisione ======== -->
                        <div class="mt-10 mb-8 border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                            <div class="bg-gray-200 px-4 py-3 border-b border-gray-300">
                                <h3 class="font-bold text-gray-700">SEO & Condivisione Social</h3>
                                <p class="text-xs text-gray-500 mt-1">Imposta come vuoi che questo contenuto appaia su Google, Facebook, WhatsApp ecc. Se lasciati vuoti, il sistema userà titolo, foto e testo automatici.</p>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <label for="seo_title" class="block text-gray-700 text-sm font-bold mb-2">Titolo per Motori di Ricerca/Social</label>
                                    <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $articolo->seo_title) }}" placeholder="Titolo personalizzato..."
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('seo_title') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_description" class="block text-gray-700 text-sm font-bold mb-2">Descrizione/Riassunto Social (max 160 caratteri ideali)</label>
                                    <textarea name="seo_description" id="seo_description" rows="3"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('seo_description', $articolo->seo_description) }}</textarea>
                                    @error('seo_description') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_image" class="block text-gray-700 text-sm font-bold mb-2">Immagine Anteprima Social</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="seo_image" id="seo_image" value="{{ old('seo_image', $articolo->seo_image) }}" readonly placeholder="Seleziona Immagine da File Manager..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <button type="button" id="fm-seo-image-button" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                            Scegli Immagine
                                        </button>
                                        <button type="button" id="fm-seo-image-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if($articolo->seo_image)
                                        <div class="mt-2 text-sm text-gray-600">
                                            <img src="{{ asset($articolo->seo_image) }}" class="h-20 w-auto object-cover mt-1 border rounded shadow-sm">
                                        </div>
                                    @endif
                                    @error('seo_image') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pb-4">
                            <x-primary-button>
                                {{ __('Aggiorna Articolo') }}
                            </x-primary-button>
                            <a href="{{ route('admin.articoli.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                                Annulla
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Gestione Widget Aggiuntivi</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Lista Widget Esistenti -->
                    @if($articolo->widgets->count() > 0)
                        <div class="mb-8">
                            <h4 class="font-bold mb-3 text-gray-700">Widget Inseriti</h4>
                            <div class="space-y-4">
                                @foreach($articolo->widgets as $widget)
                                    <div class="border rounded p-4 bg-gray-50 shadow-sm flex justify-between items-center relative pl-8">
                                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-move" title="Trascina per ordinare (Implementazione futura)">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                        </div>
                                        <div>
                                            @if($widget->tipo === 'global_widget')
                                                @php $gw = \App\Models\GlobalWidget::find($widget->data['global_widget_id'] ?? null); @endphp
                                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded uppercase font-bold mr-2">Globale: {{ $gw->tipo ?? '?' }}</span>
                                                <strong class="text-gray-800">{{ $gw->titolo ?? '(Eliminato)' }}</strong>
                                            @else
                                                @php $isShop = str_starts_with($widget->tipo, 'shop_'); @endphp
                                                <span class="inline-block {{ $isShop ? 'bg-orange-100 text-orange-800' : 'bg-indigo-100 text-indigo-800' }} text-xs px-2 py-1 rounded uppercase font-bold mr-2">{{ $widget->tipo }}</span>
                                                <strong class="text-gray-800">{{ $widget->titolo }}</strong>
                                            @endif
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST" onsubmit="return confirm('Vuoi davvero eliminare questo widget?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic mb-8">Nessun widget aggiunto a questo articolo.</p>
                    @endif

                    <!-- Aggiunta Nuovi Widget -->
                    <div class="border-t pt-6" x-data="{ activeTab: 'gallery' }">
                        <h4 class="font-bold mb-4 text-gray-700">Aggiungi un nuovo Widget</h4>
                        
                        <!-- Grid Selector -->
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                            <!-- Gallery Foto -->
                            <button @click="activeTab = 'gallery'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'gallery', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'gallery'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'gallery' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'gallery' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'gallery' ? 'text-indigo-700' : 'text-gray-600'">Gallery Foto</span>
                            </button>

                            <!-- Video -->
                            <button @click="activeTab = 'video'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'video', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'video'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'video' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'video' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'video' ? 'text-indigo-700' : 'text-gray-600'">Video</span>
                            </button>

                            <!-- Blocchi Specchio -->
                            <button @click="activeTab = 'mirror'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'mirror', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'mirror'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'mirror' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'mirror' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'mirror' ? 'text-indigo-700' : 'text-gray-600'">Specchio</span>
                            </button>

                            <!-- Blocchi Info -->
                            <button @click="activeTab = 'info_blocks'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'info_blocks', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'info_blocks'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'info_blocks' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'info_blocks' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'info_blocks' ? 'text-indigo-700' : 'text-gray-600'">Info</span>
                            </button>

                            <!-- Blocco Singolo -->
                            <button @click="activeTab = 'single'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'single', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'single'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'single' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'single' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'single' ? 'text-indigo-700' : 'text-gray-600'">Blocco Singolo</span>
                            </button>

                             <!-- Griglia -->
                            <button @click="activeTab = 'grid'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'grid', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'grid'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'grid' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'grid' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'grid' ? 'text-indigo-700' : 'text-gray-600'">Griglia</span>
                            </button>

                             <!-- Strutture -->
                            <button @click="activeTab = 'booking_structures'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'booking_structures', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'booking_structures'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'booking_structures' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'booking_structures' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'booking_structures' ? 'text-indigo-700' : 'text-gray-600'">Strutture</span>
                            </button>

                             <!-- Mappa -->
                            <button @click="activeTab = 'map'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'map', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'map'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'map' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'map' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'map' ? 'text-indigo-700' : 'text-gray-600'">Mappa</span>
                            </button>
                             
                             @if($shop_enabled)
                                <!-- Collezione Shop -->
                                <button @click="activeTab = 'shop_collection'" 
                                    :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_collection', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_collection'}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                    <div :class="activeTab === 'shop_collection' ? 'bg-orange-500' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                        <svg class="w-6 h-6" :class="activeTab === 'shop_collection' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase" :class="activeTab === 'shop_collection' ? 'text-orange-700' : 'text-gray-600'">Shop Collezione</span>
                                </button>

                                <!-- Prodotti Evidenza -->
                                <button @click="activeTab = 'shop_products'" 
                                    :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_products', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_products'}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                    <div :class="activeTab === 'shop_products' ? 'bg-orange-500' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                        <svg class="w-6 h-6" :class="activeTab === 'shop_products' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase" :class="activeTab === 'shop_products' ? 'text-orange-700' : 'text-gray-600'">Shop Prodotti</span>
                                </button>

                                <!-- Marche -->
                                <button @click="activeTab = 'shop_brands'" 
                                    :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_brands', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_brands'}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                    <div :class="activeTab === 'shop_brands' ? 'bg-orange-500' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                        <svg class="w-6 h-6" :class="activeTab === 'shop_brands' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase" :class="activeTab === 'shop_brands' ? 'text-orange-700' : 'text-gray-600'">Shop Marche</span>
                                </button>
                                
                                <!-- Annuncio (Top Announcement) -->
                                <button @click="activeTab = 'announcement'" 
                                    :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'announcement', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'announcement'}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                    <div :class="activeTab === 'announcement' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                        <svg class="w-6 h-6" :class="activeTab === 'announcement' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.167M12 13h1m8-7V5a2 2 0 00-2-2H9a2 2 0 00-2 2v1h10a2 2 0 012 2v3m2 4h-2.343M11 13v9m0-9a3 3 0 013-3h3a3 3 0 013 3v9m-9 0h9"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold" :class="activeTab === 'announcement' ? 'text-indigo-700' : 'text-gray-600'">Annuncio Top</span>
                                </button>
                             @endif

                            <!-- Global Widget Selector (Special) -->
                            <button @click="activeTab = 'global'" 
                                :class="{'ring-2 ring-green-500 bg-green-50 border-green-200': activeTab === 'global', 'bg-white border-gray-200 hover:border-green-300': activeTab !== 'global'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group ml-auto">
                                <div :class="activeTab === 'global' ? 'bg-green-600' : 'bg-green-100 group-hover:bg-green-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'global' ? 'text-white' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'global' ? 'text-green-700' : 'text-gray-600'">Widget Globale</span>
                            </button>
                        </div>

                        <!-- Form Gallery -->
                        <div x-show="activeTab === 'gallery'" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="gallery">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Gallery *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
                                <div id="gallery-container" class="space-y-3 mb-4">
                                    <div class="flex items-center space-x-2 gallery-row">
                                        <div class="flex-1 flex flex-col space-y-2">
                                            <div class="flex">
                                                <input type="text" name="data[photos][0][url]" readonly required placeholder="URL Foto dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                                <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">📸 Foto</button>
                                            </div>
                                            <div class="flex">
                                                <input type="text" name="data[photos][0][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                                <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">🎥 Video</button>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="data[photos][0][link]" placeholder="Link Opzionale (http://...)" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-sm h-full">
                                        </div>
                                        <button type="button" class="btn-rimuovi-foto text-red-500 opacity-50 hover:opacity-100" title="Rimuovi"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <button type="button" id="btn-aggiungi-foto" class="text-indigo-600 font-bold hover:text-indigo-800 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi un'altra foto
                                    </button>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Gallery</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Top Announcement -->
                        <div x-show="activeTab === 'announcement'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="top_announcement">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Interno Admin *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="es. Messaggio di benvenuto">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Messaggio Comunicazione *</label>
                                        <input type="text" name="data[message]" required placeholder="Es: Spedizione gratuita per ordini sopra i 50€!" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo</label>
                                        <select name="data[bg_color]" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="bg-indigo-600">Indaco (Default)</option>
                                            <option value="bg-gray-900">Nero</option>
                                            <option value="bg-red-600">Rosso (Emergenza)</option>
                                            <option value="bg-yellow-500">Giallo (Avviso)</option>
                                            <option value="bg-green-600">Verde (Successo)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Icona (Emoji)</label>
                                        <input type="text" name="data[icon]" placeholder="Es: 📣 o 🚚" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Testo Bottone (Opzionale)</label>
                                        <input type="text" name="data[button_text]" placeholder="Es: Scopri di più" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Link Bottone</label>
                                        <input type="text" name="data[button_url]" placeholder="Es: /shop o https://..." class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Annuncio</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Video -->
                        <div x-show="activeTab === 'video'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="video">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Video *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">File Video (MP4) *</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="video_url" name="data[video_url]" readonly required placeholder="URL Video dal File Manager (es: .mp4)" class="shadow appearance-none border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-video" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none">
                                            <span>Scegli MP4...</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Video</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Mirror -->
                        <div x-show="activeTab === 'mirror'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="mirror_blocks">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Blocchi (Opzionale)</label>
                                    <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline" placeholder="Lascia vuoto per non mostrare un titolo globale">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                        <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="">-- Scegli da quale sezione pescare articoli --</option>
                                            @foreach(\App\Models\Section::all() as $sez)
                                                <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Numero di Articoli *</label>
                                        <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            @for($i=1; $i<=10; $i++)
                                                <option value="{{ $i }}">{{ $i }} Articoli</option>
                                            @endfor
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Mostrerà fino a questo numero di articoli presenti nella sezione scelta.</p>
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Blocchi a Specchio</button>
                                </div>
                        </form>
                    </div>
                    
                    <!-- Form Info Blocks -->
                    <div x-show="activeTab === 'info_blocks'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="info_blocks">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Sezione (Opzionale)</label>
                                <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Numero Colonne (Desktop) *</label>
                                <select name="data[columns]" required class="shadow border rounded w-full md:w-1/4 py-2 px-3 focus:outline-none">
                                    <option value="1">1 Colonna</option>
                                    <option value="2">2 Colonne</option>
                                    <option value="3" selected>3 Colonne</option>
                                    <option value="4">4 Colonne</option>
                                    <option value="6">6 Colonne</option>
                                </select>
                            </div>
                            
                            <div id="info-blocks-container" class="space-y-4 mb-4">
                                <div class="bg-white p-4 rounded border border-gray-200 shadow-sm info-block-row">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <div class="md:col-span-4">
                                            <label class="block text-gray-700 text-xs font-bold mb-1">Icona / Immagine</label>
                                            <div class="flex">
                                                <input type="text" name="data[items][0][image]" readonly placeholder="Scegli icona..." class="shadow border rounded-l w-full py-2 px-3 bg-gray-50 focus:outline-none text-xs">
                                                <button type="button" class="btn-sfoglia-block bg-gray-200 hover:bg-gray-300 px-3 rounded-r border border-l-0 text-xs">📸</button>
                                            </div>
                                        </div>
                                        <div class="md:col-span-7">
                                            <label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco (Accetta HTML)</label>
                                            <textarea name="data[items][0][text]" rows="2" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-xs" placeholder="Inserisci testo..."></textarea>
                                        </div>
                                        <div class="md:col-span-1 flex items-center justify-center">
                                            <button type="button" class="btn-red-100 opacity-20 pointer-events-none" title="Non eliminabile">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="btn-aggiungi-block" class="text-indigo-600 font-bold hover:text-indigo-800 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi un altro blocco
                                </button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Widget Blocchi</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Booking Structures -->
                    <div x-show="activeTab === 'booking_structures'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="booking_structures">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Sezione (Opzionale)</label>
                                <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero di Strutture *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>{{ $i }} Strutture</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Strutture per riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Widget Strutture</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Map -->
                    <div x-show="activeTab === 'map'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="map">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Mappa (Opzionale)</label>
                                <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline" placeholder="es. Dove siamo">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Codice Embed Google Maps (iframe) *</label>
                                <textarea name="data[embed_code]" required rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline text-xs" placeholder='Incolla qui il codice <iframe src="..."></iframe>'></textarea>
                                <p class="text-xs text-gray-500 mt-1">Vai su Google Maps -> Condividi -> Incorpora una mappa -> Copia HTML.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Altezza (px)</label>
                                    <input type="number" name="data[height]" value="450" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="flex items-center mt-6">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="data[full_width]" value="1" class="form-checkbox h-5 w-5 text-indigo-600">
                                        <span class="ml-2 text-gray-700 text-sm font-bold">Tutta Larghezza (Full Width)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Widget Mappa</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Single Block -->
                    <div x-show="activeTab === 'single'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="single_block">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Blocco *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale)</label>
                                <input type="text" name="data[subtitle]" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Link al click (Opzionale)</label>
                                <input type="url" name="data[link]" placeholder="https://..." class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Sfondo / Principale</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_image" name="data[image]" readonly required placeholder="URL Immagine dal File Manager" class="shadow appearance-none border rounded-l w-full py-2 px-3 bg-white focus:outline-none focus:shadow-outline">
                                        <button type="button" id="btn-sfoglia-single" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none">
                                            <span>Scegli Foto...</span>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Sfondo (Opzionale)</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_video" name="data[video]" readonly placeholder="URL Video (.mp4)" class="shadow appearance-none border rounded-l w-full py-2 px-3 bg-white focus:outline-none focus:shadow-outline">
                                        <button type="button" id="btn-sfoglia-single-video" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none">
                                            <span>Scegli Video...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo Blocco (HEX)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="data[bg_color]" value="#ffffff" class="h-10 w-10 border rounded cursor-pointer">
                                    <span class="text-sm text-gray-500">Scegli un colore di sfondo.</span>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Blocco Singolo</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Section Grid -->
                    <div x-show="activeTab === 'grid'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="section_grid">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Griglia *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    <option value="">-- Seleziona da dove pescare articoli --</option>
                                    @foreach(\App\Models\Section::all() as $sez)
                                        <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}">{{ $i }} Articoli</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per Riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Griglia Sezione</button>
                            </div>
                        </form>
                    </div>

                        <!-- Form Shop Collection -->
                        @if($shop_enabled)
                        <div x-show="activeTab === 'shop_collection'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_collection">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Sezione (Opzionale)</label>
                                    <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona Collezione *</label>
                                        <select name="data[shop_collection_id]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="">-- Seleziona una Collezione --</option>
                                            @foreach($shop_collections as $col)
                                                <option value="{{ $col->id }}">{{ $col->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Limite Prodotti *</label>
                                        <input type="number" name="data[limit]" value="4" min="1" max="20" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Collezione</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Shop Featured Products -->
                        <div x-show="activeTab === 'shop_products'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner" x-data="{ selectionMode: 'manual' }">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_featured_products">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Widget (Opzionale)</label>
                                    <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Modalità di selezione</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" x-model="selectionMode" name="data[mode]" value="manual" class="form-radio text-orange-600">
                                            <span class="ml-2 text-sm">Selezione Manuale</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" x-model="selectionMode" name="data[mode]" value="category" class="form-radio text-orange-600">
                                            <span class="ml-2 text-sm">Per Categoria</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Selezione Manuale -->
                                <div x-show="selectionMode === 'manual'" class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona i Prodotti da mostrare:</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 bg-white p-4 rounded border max-h-60 overflow-y-auto shadow-inner">
                                        @foreach($shop_products as $p)
                                            <label class="flex items-center p-1 hover:bg-orange-50 rounded cursor-pointer transition-colors">
                                                <input type="checkbox" name="data[product_ids][]" value="{{ $p->id }}" class="rounded border-gray-300 text-orange-600">
                                                <span class="ml-2 text-xs text-gray-700 truncate" title="{{ $p->nome }}">{{ $p->nome }}</span>
                                            </label>
                                        @endforeach
                                        @if($shop_products->isEmpty())
                                            <p class="text-xs text-gray-500 italic">Nessun prodotto disponibile nello shop.</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Selezione Categoria -->
                                <div x-show="selectionMode === 'category'" class="grid grid-cols-2 gap-4 mb-4" style="display: none;">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Categoria/Sottocategoria *</label>
                                        <select name="data[shop_category_id]" x-bind:required="selectionMode === 'category'" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="">-- Scegli Categoria --</option>
                                            @foreach($shop_categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                                @foreach($cat->children as $sub)
                                                    <option value="{{ $sub->id }}">-- {{ $sub->nome }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Numero Prodotti *</label>
                                        <input type="number" name="data[cat_limit]" value="4" min="1" max="20" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Prodotti</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Shop Brands -->
                        <div x-show="activeTab === 'shop_brands'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_brands">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Sezione (Opzionale)</label>
                                    <input type="text" name="titolo" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="Es. Le nostre Marche">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stile Visualizzazione</label>
                                    <select name="data[style]" class="shadow border rounded w-full md:w-1/3 py-2 px-3 focus:outline-none">
                                        <option value="grid">Griglia Loghi</option>
                                        <option value="carousel">Slider Carosello</option>
                                    </select>
                                </div>
                                <p class="text-xs text-gray-500 mb-4 italic">Verranno mostrate tutte le marche impostate come "Visibili" nella gestione marche.</p>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Marche</button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Form Global -->
                        <div x-show="activeTab === 'global'" style="display: none;" class="bg-green-50 p-6 rounded-lg border border-green-100 shadow-inner">
                            <form action="{{ route('admin.widgets.store', $articolo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="global_widget">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona dalla Libreria *</label>
                                    <select name="data[global_widget_id]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="">-- Seleziona un Widget Globale --</option>
                                        @foreach(\App\Models\GlobalWidget::orderBy('titolo')->get() as $gw)
                                            <option value="{{ $gw->id }}">[{{ strtoupper($gw->tipo) }}] {{ $gw->titolo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Collega all'Articolo</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            let editorInstance;
            let fmActiveTarget = 'editor';
            let fmActiveInput = null; // Used for dynamic gallery/video inputs
            let photoIndex = 1; // Initialize index for new gallery photos
            let blockIndex = 1; // Initialize index for new info blocks
            
            ClassicEditor
                .create( document.querySelector( '#descrizione' ) )
                .then( editor => {
                    editorInstance = editor;
                } )
                .catch( error => {
                    console.error( error );
                } );

            document.addEventListener("DOMContentLoaded", function() {
                // Article core buttons
                document.getElementById('fm-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'editor';
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-foto-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'foto';
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-allegato-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'allegato';
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-seo-image-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'seo_image';
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });

                document.getElementById('fm-seo-image-clear').addEventListener('click', (event) => {
                    event.preventDefault();
                    document.getElementById('seo_image').value = '';
                });

                document.getElementById('fm-foto-clear').addEventListener('click', (event) => {
                    event.preventDefault();
                    document.getElementById('foto').value = '';
                });

                document.getElementById('fm-video-clear').addEventListener('click', (event) => {
                    event.preventDefault();
                    document.getElementById('video').value = '';
                });

                document.getElementById('fm-allegato-clear').addEventListener('click', (event) => {
                    event.preventDefault();
                    document.getElementById('allegato').value = '';
                });

                document.getElementById('fm-video-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'video';
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });

                // --- Widget Video ---
                document.getElementById('btn-sfoglia-video').addEventListener('click', (e) => {
                    e.preventDefault();
                    fmActiveTarget = 'widget_video';
                    fmActiveInput = document.getElementById('video_url');
                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });

                // --- Widget Blocco Singolo ---
                if (document.getElementById('btn-sfoglia-single')) {
                    document.getElementById('btn-sfoglia-single').addEventListener('click', (e) => {
                        e.preventDefault();
                        fmActiveTarget = 'widget_single';
                        fmActiveInput = document.getElementById('single_block_image');
                        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                    });
                }
                if (document.getElementById('btn-sfoglia-single-video')) {
                    document.getElementById('btn-sfoglia-single-video').addEventListener('click', (e) => {
                        e.preventDefault();
                        fmActiveTarget = 'widget_single_video';
                        fmActiveInput = document.getElementById('single_block_video');
                        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                    });
                }

                // --- Widget Gallery: Dynamic Rows ---
                document.addEventListener('click', (e) => {
                    const btnAdd = e.target.closest('#btn-aggiungi-foto');
                    if (btnAdd) {
                        e.preventDefault();
                        const galleryContainer = document.getElementById('gallery-container');
                        if (!galleryContainer) return;
                        const row = document.createElement('div');
                        row.className = 'flex items-center space-x-2 gallery-row mt-3';
                        row.innerHTML = `
                            <div class="flex-1 flex flex-col space-y-2">
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][url]" readonly required placeholder="URL Foto dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">📸 Foto</button>
                                </div>
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">🎥 Video</button>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="data[photos][${photoIndex}][link]" placeholder="Link Opzionale (http://...)" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-sm h-full">
                            </div>
                            <button type="button" class="btn-rimuovi-foto text-red-500 opacity-50 hover:opacity-100" title="Rimuovi"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                        `;
                        galleryContainer.appendChild(row);
                        photoIndex++;
                        return;
                    }

                    // Sfoglia button (Delegated)
                    const btnSfoglia = e.target.closest('.btn-sfoglia-gallery');
                    if(btnSfoglia) {
                        e.preventDefault();
                        fmActiveTarget = 'widget_gallery';
                        fmActiveInput = btnSfoglia.previousElementSibling; 
                        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                        return;
                    }

                    // Sfoglia video gallery button (Delegated)
                    const btnSfogliaVid = e.target.closest('.btn-sfoglia-video-gallery');
                    if(btnSfogliaVid) {
                        e.preventDefault();
                        fmActiveTarget = 'widget_gallery_video';
                        fmActiveInput = btnSfogliaVid.previousElementSibling;
                        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                        return;
                    }

                    // Rimuovi button (Delegated)
                    const btnRimuovi = e.target.closest('.btn-rimuovi-foto');
                    if(btnRimuovi) {
                        e.preventDefault();
                        const row = btnRimuovi.closest('.gallery-row');
                        if(document.querySelectorAll('.gallery-row').length > 1) {
                            row.remove();
                        } else {
                            alert('Devi lasciare almeno una foto nella gallery.');
                        }
                        return;
                    }

                    // --- Widget Info Blocks: Dynamic Rows ---
                    const btnAddBlock = e.target.closest('#btn-aggiungi-block');
                    if (btnAddBlock) {
                        e.preventDefault();
                        const blockContainer = document.getElementById('info-blocks-container');
                        if (!blockContainer) return;
                        const row = document.createElement('div');
                        row.className = 'bg-white p-4 rounded border border-gray-200 shadow-sm info-block-row mt-4';
                        row.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-gray-700 text-xs font-bold mb-1">Icona / Immagine</label>
                                    <div class="flex">
                                        <input type="text" name="data[items][${blockIndex}][image]" readonly placeholder="Scegli icona..." class="shadow border rounded-l w-full py-2 px-3 bg-gray-50 focus:outline-none text-xs">
                                        <button type="button" class="btn-sfoglia-block bg-gray-200 hover:bg-gray-300 px-3 rounded-r border border-l-0 text-xs">📸</button>
                                    </div>
                                </div>
                                <div class="md:col-span-7">
                                    <label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco (Accetta HTML)</label>
                                    <textarea name="data[items][${blockIndex}][text]" rows="2" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-xs" placeholder="Inserisci testo..."></textarea>
                                </div>
                                <div class="md:col-span-1 flex items-center justify-center">
                                    <button type="button" class="btn-rimuovi-block text-red-500 hover:text-red-700" title="Rimuovi Block">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                        blockContainer.appendChild(row);
                        blockIndex++;
                        return;
                    }

                    // Sfoglia block button (Delegated)
                    const btnSfogliaBlock = e.target.closest('.btn-sfoglia-block');
                    if(btnSfogliaBlock) {
                        e.preventDefault();
                        fmActiveTarget = 'widget_info_block';
                        fmActiveInput = btnSfogliaBlock.previousElementSibling;
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                        return;
                    }

                    // Rimuovi block button (Delegated)
                    const btnRimuoviBlock = e.target.closest('.btn-rimuovi-block');
                    if(btnRimuoviBlock) {
                        e.preventDefault();
                        btnRimuoviBlock.closest('.info-block-row').remove();
                        return;
                    }
                });
            });

            // Callback function expected by FileManager popup
            function fmSetLink($url) {
                const baseUrl = '{{ config('app.url') }}';
                let relativeUrl = $url.replace(baseUrl, '');
                
                if (!relativeUrl.startsWith('http')) {
                    let cleanPath = relativeUrl.replace(/^\/+/, '');
                    if (!cleanPath.startsWith('storage/')) {
                        relativeUrl = '/storage/' + cleanPath;
                    } else {
                        relativeUrl = '/' + cleanPath;
                    }
                }
                
                if (fmActiveTarget === 'editor') {
                    if(editorInstance) {
                        editorInstance.model.change( writer => {
                            const imageElement = writer.createElement( 'imageBlock', {
                                src: $url // Qui manteniamo l'assoluto per l'editor CKEditor se serve, o usiamo relativo
                            } );
                            editorInstance.model.insertContent( imageElement, editorInstance.model.document.selection );
                        } );
                    }
                } else if (fmActiveTarget === 'foto') {
                    document.getElementById('foto').value = relativeUrl;
                } else if (fmActiveTarget === 'video') {
                    document.getElementById('video').value = relativeUrl;
                } else if (fmActiveTarget === 'allegato') {
                    document.getElementById('allegato').value = relativeUrl;
                } else if (fmActiveTarget === 'seo_image') {
                    document.getElementById('seo_image').value = relativeUrl;
                } else if (fmActiveTarget === 'widget_video' || fmActiveTarget === 'widget_gallery' || fmActiveTarget === 'widget_single' || fmActiveTarget === 'widget_single_video' || fmActiveTarget === 'widget_gallery_video' || fmActiveTarget === 'widget_info_block') {
                    if(fmActiveInput) {
                        fmActiveInput.value = relativeUrl;
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
