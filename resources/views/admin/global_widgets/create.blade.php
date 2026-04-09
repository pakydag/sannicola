<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crea Nuovo Widget Globale
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200" x-data="{ activeTab: 'gallery' }">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex border-b mb-6 border-indigo-200">
                        <button @click="activeTab = 'gallery'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'gallery', 'text-gray-600 hover:text-indigo-600': activeTab !== 'gallery'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">📸 Gallery Foto</button>
                        <button @click="activeTab = 'video'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'video', 'text-gray-600 hover:text-indigo-600': activeTab !== 'video'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🎥 Video</button>
                        <button @click="activeTab = 'mirror'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'mirror', 'text-gray-600 hover:text-indigo-600': activeTab !== 'mirror'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">☯️ Blocchi Specchio</button>
                        <button @click="activeTab = 'single'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'single', 'text-gray-600 hover:text-indigo-600': activeTab !== 'single'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🟦 Blocco Singolo</button>
                        <button @click="activeTab = 'grid'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'grid', 'text-gray-600 hover:text-indigo-600': activeTab !== 'grid'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🔲 Griglia Sezione</button>
                        <button @click="activeTab = 'info_blocks'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'info_blocks', 'text-gray-600 hover:text-indigo-600': activeTab !== 'info_blocks'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🧱 Blocchi Info</button>
                        <button @click="activeTab = 'image_text_image'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'image_text_image', 'text-gray-600 hover:text-indigo-600': activeTab !== 'image_text_image'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🖼️ Foto-Testo-Foto</button>
                        @if(config('app.booking_enabled') === '1' || \App\Models\Setting::where('key', 'booking_enabled')->value('value') == '1')
                            <button @click="activeTab = 'booking_search'" :class="{'bg-indigo-50 border-t border-l border-r border-indigo-200 text-indigo-700 font-bold': activeTab === 'booking_search', 'text-gray-600 hover:text-indigo-600': activeTab !== 'booking_search'}" class="py-2 px-4 rounded-t-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-1 text-sm">🔎 Ricerca Booking</button>
                        @endif
                    </div>

                    <!-- Form Gallery -->
                    <div x-show="activeTab === 'gallery'" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="gallery">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div id="gallery-container" class="space-y-3 mb-4">
                                <div class="flex items-center space-x-2 gallery-row">
                                    <div class="flex-1 flex flex-col space-y-2 text-sm">
                                        <div class="flex">
                                            <input type="text" name="data[photos][0][url]" readonly required placeholder="URL Foto dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">📸 Foto</button>
                                        </div>
                                        <div class="flex">
                                            <input type="text" name="data[photos][0][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">🎥 Video</button>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" name="data[photos][0][link]" placeholder="Link Opzionale" class="shadow border rounded w-full py-2 px-3 h-full focus:outline-none text-sm">
                                    </div>
                                    <button type="button" class="btn-rimuovi-foto text-red-500 opacity-50 hover:opacity-100"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="btn-aggiungi-foto" class="text-indigo-600 font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi Silde</button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Salva Gallery Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Video -->
                    <div x-show="activeTab === 'video'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="video">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">File Video (MP4) *</label>
                                <div class="flex mt-1 relative rounded-md shadow-sm">
                                    <input type="text" id="video_url" name="data[video_url]" readonly required class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                    <button type="button" id="btn-sfoglia-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli MP4...</button>
                                </div>
                            </div>
                            <div class="mb-4 flex space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[autoplay]" value="1" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Autoplay (Parte in automatico)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[loop]" value="1" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Loop (Ripeti all'infinito)</span>
                                </label>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Video Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Mirror Blocks -->
                    <div x-show="activeTab === 'mirror'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="mirror_blocks">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                    <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="">-- Seleziona --</option>
                                        @foreach(\App\Models\Section::all() as $sez)
                                            <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Specchio Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Single Block -->
                    <div x-show="activeTab === 'single'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="single_block">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Blocco *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale)</label>
                                <input type="text" name="data[subtitle]" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Link al click (Opzionale)</label>
                                <input type="url" name="data[link]" placeholder="https://..." class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Sfondo / Principale</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_image" name="data[image]" readonly required placeholder="Immagine dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Foto...</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Sfondo (Opzionale)</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_video" name="data[video]" readonly placeholder="URL Video (.mp4)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Video...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo Blocco (HEX)</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="data[bg_color]" value="#ffffff" class="h-10 w-10 border rounded cursor-pointer">
                                        <span class="text-sm text-gray-500">Scegli un colore di sfondo.</span>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Disposizione Contenuto</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="side" checked class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto a sinistra (Full)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="stacked" class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto sopra (Stacked)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Blocco Singolo</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Section Grid -->
                    <div x-show="activeTab === 'grid'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="section_grid">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Griglia *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                    <option value="">-- Seleziona --</option>
                                    @foreach(\App\Models\Section::all() as $sez)
                                        <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per Riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Griglia Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Info Blocks -->
                    <div x-show="activeTab === 'info_blocks'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="info_blocks">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
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
                                            <button type="button" class="text-red-100 opacity-20 pointer-events-none" title="Non eliminabile">
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

                    <!-- Form Image Text Image -->
                    <div x-show="activeTab === 'image_text_image'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="image_text_image">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <hr class="my-6 border-indigo-200">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Elemento Sinistra -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Sinistra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_left" name="data[img_left]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_left">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_left" name="data[video_left]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_left">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_left]" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>

                                <!-- Elemento Centrale (Testo) -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Testo Centrale</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Titolo in Evidenza</label>
                                        <input type="text" name="data[center_title]" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Sottotitolo / Descrizione</label>
                                        <textarea name="data[center_subtitle]" rows="3" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Colore Testo (HEX)</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="data[text_color]" value="#111827" class="h-8 w-8 border rounded cursor-pointer">
                                        </div>
                                    </div>
                                </div>

                                <!-- Elemento Destra -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Destra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_right" name="data[img_right]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_right">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_right" name="data[video_right]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_right">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_right]" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right mt-6">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Foto-Testo-Foto</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Booking Search -->
                    <div x-show="activeTab === 'booking_search'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="booking_search">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Questo widget mostrerà un motore di ricerca disponibilità (Check-in, Check-out, Ospiti). Sarà visibile sul sito pubblico solo se il modulo <strong>Booking</strong> è abilitato nelle impostazioni generali.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Testo in Evidenza (Titolo Interno)</label>
                                    <input type="text" name="data[title]" placeholder="es. Trova la tua struttura perfetta" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo</label>
                                    <input type="text" name="data[subtitle]" placeholder="es. Inserisci le date per verificare la disponibilità" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
                            </div>

                            <div class="text-right mt-6">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Motore di Ricerca</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            let fmActiveTarget = null;
            let fmActiveInput = null;

            document.addEventListener("DOMContentLoaded", function() {
                const btnVideo = document.getElementById('btn-sfoglia-video');
                if (btnVideo) {
                    btnVideo.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('video_url');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const btnSingle = document.getElementById('btn-sfoglia-single');
                if (btnSingle) {
                    btnSingle.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('single_block_image');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }
                const btnSingleVideo = document.getElementById('btn-sfoglia-single-video');
                if (btnSingleVideo) {
                    btnSingleVideo.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('single_block_video');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const galleryContainer = document.getElementById('gallery-container');
                let photoIndex = 1;

                if (document.getElementById('btn-aggiungi-foto')) {
                    document.getElementById('btn-aggiungi-foto').addEventListener('click', () => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center space-x-2 gallery-row mt-3';
                        row.innerHTML = `
                            <div class="flex-1 flex flex-col space-y-2 text-sm">
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][url]" readonly required placeholder="URL Foto" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0">📸 Foto</button>
                                </div>
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0">🎥 Video</button>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="data[photos][${photoIndex}][link]" placeholder="Link Opzionale" class="shadow border rounded w-full py-2 px-3 h-full focus:outline-none text-sm">
                            </div>
                            <button type="button" class="btn-rimuovi-foto text-red-500 hover:text-red-700"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                        `;
                        galleryContainer.appendChild(row);
                        photoIndex++;
                    });
                }

                if (galleryContainer) {
                    galleryContainer.addEventListener('click', (e) => {
                        if(e.target.closest('.btn-sfoglia-gallery')) {
                            e.preventDefault();
                            const btn = e.target.closest('.btn-sfoglia-gallery');
                            fmActiveInput = btn.previousElementSibling;
                            window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                        }
                        if(e.target.closest('.btn-sfoglia-video-gallery')) {
                            e.preventDefault();
                            const btn = e.target.closest('.btn-sfoglia-video-gallery');
                            fmActiveInput = btn.previousElementSibling;
                            window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                        }
                        if(e.target.closest('.btn-rimuovi-foto')) {
                            e.preventDefault();
                            if(document.querySelectorAll('.gallery-row').length > 1) {
                                e.target.closest('.gallery-row').remove();
                            } else {
                                alert('Almeno una slide necessaria.');
                            }
                        }
                    });
                }
                const itBottons = document.querySelectorAll('.btn-sfoglia-iti');
                itBottons.forEach(btn => {
                    btn.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById(event.target.getAttribute('data-target'));
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                });

                // --- Widget Info Blocks: Dynamic Rows ---
                let blockIndex = 1;
                const btnAddBlock = document.getElementById('btn-aggiungi-block');
                if (btnAddBlock) {
                    btnAddBlock.addEventListener('click', (e) => {
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
                    });
                }

                // Handling dynamic clicks for blocks
                document.body.addEventListener('click', (e) => {
                    const btnSfoglia = e.target.closest('.btn-sfoglia-block');
                    if (btnSfoglia) {
                        e.preventDefault();
                        fmActiveInput = btnSfoglia.previousElementSibling;
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    }
                    const btnRimuovi = e.target.closest('.btn-rimuovi-block');
                    if (btnRimuovi) {
                        e.preventDefault();
                        btnRimuovi.closest('.info-block-row').remove();
                    }
                });

            });

            function fmSetLink($url) {
                if (fmActiveInput) {
                    // Fix absolute URL: Rimuove protocollo e dominio per avere il percorso relativo (/storage/...)
                    $url = $url.replace(/^https?:\/\/[^\/]+/, "");
                    fmActiveInput.value = $url;
                }
            }
        </script>
    @endpush
</x-app-layout>
