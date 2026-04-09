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
                                                <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded uppercase font-bold mr-2">{{ $widget->tipo }}</span>
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
                        
                        <!-- Tabs -->
                        <div class="flex border-b mb-6 border-indigo-200">
                            <button @click="activeTab = 'gallery'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'gallery', 'text-gray-600 hover:text-indigo-600': activeTab !== 'gallery'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">📸 Gallery Foto</button>
                            <button @click="activeTab = 'video'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'video', 'text-gray-600 hover:text-indigo-600': activeTab !== 'video'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🎥 Video</button>
                            <button @click="activeTab = 'mirror'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'mirror', 'text-gray-600 hover:text-indigo-600': activeTab !== 'mirror'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">☯️ Blocchi Specchio</button>
                            <button @click="activeTab = 'info_blocks'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'info_blocks', 'text-gray-600 hover:text-indigo-600': activeTab !== 'info_blocks'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🧱 Blocchi Info</button>
                            <button @click="activeTab = 'single'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'single', 'text-gray-600 hover:text-indigo-600': activeTab !== 'single'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🟦 Blocco Singolo</button>
                             <button @click="activeTab = 'grid'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'grid', 'text-gray-600 hover:text-indigo-600': activeTab !== 'grid'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🔲 Griglia</button>
                             <button @click="activeTab = 'booking_structures'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'booking_structures', 'text-gray-600 hover:text-indigo-600': activeTab !== 'booking_structures'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🏨 Strutture</button>
                             <button @click="activeTab = 'map'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'map', 'text-gray-600 hover:text-indigo-600': activeTab !== 'map'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">📍 Mappa</button>
                             <button @click="activeTab = 'global'" :class="{'bg-green-50 border-t border-l border-r border-green-200 text-green-700 font-bold': activeTab === 'global', 'text-gray-600 hover:text-green-600': activeTab !== 'global'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 ml-auto mr-1 text-sm">🌍 Globale</button>
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
                        window.open('{{ route('admin.filemanager.button') }}', 'fm', 'width=1400,height=800');
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
                let relativeUrl = $url;
                try {
                    const urlObj = new URL($url);
                    relativeUrl = urlObj.pathname;
                } catch (e) {
                    console.error("Invalid URL passed to fmSetLink:", $url);
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
