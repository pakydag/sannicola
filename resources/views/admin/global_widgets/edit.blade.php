<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifica Widget Globale: {{ $globalWidget->titolo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded uppercase font-bold">Tipo: {{ $globalWidget->tipo }}</span>
                    </div>

                    <form action="{{ route('admin.global-widgets.update', $globalWidget) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                            <input type="text" name="titolo" value="{{ old('titolo', $globalWidget->titolo) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                        </div>

                        <!-- Gallery -->
                        @if($globalWidget->tipo === 'gallery')
                            <div id="gallery-container" class="space-y-3 mb-4">
                                @php
                                    $photos = old('data.photos', $globalWidget->data['photos'] ?? []);
                                    if(empty($photos)) $photos = [['url' => '', 'video_url' => '', 'link' => '']];
                                @endphp
                                @foreach($photos as $index => $photo)
                                    <div class="flex items-center space-x-2 gallery-row mb-3">
                                        <div class="flex-1 flex flex-col space-y-2 text-sm">
                                            <div class="flex">
                                                <input type="text" name="data[photos][{{ $index }}][url]" value="{{ $photo['url'] ?? '' }}" readonly required placeholder="URL Foto" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                                <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0 text-sm whitespace-nowrap">📸 Foto</button>
                                            </div>
                                            <div class="flex">
                                                <input type="text" name="data[photos][{{ $index }}][video_url]" value="{{ $photo['video_url'] ?? '' }}" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                                <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0 text-sm whitespace-nowrap">🎥 Video</button>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="data[photos][{{ $index }}][link]" value="{{ $photo['link'] ?? '' }}" placeholder="Link Opzionale" class="shadow border rounded w-full py-2 px-3 h-full focus:outline-none text-sm">
                                        </div>
                                        <button type="button" class="btn-rimuovi-foto text-red-500 hover:text-red-700"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="btn-aggiungi-foto" class="text-indigo-600 font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi Slide</button>
                            </div>

                        <!-- Video -->
                        @elseif($globalWidget->tipo === 'video')
                            <div class="mb-4 mt-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">File Video (MP4) *</label>
                                <div class="flex mt-1 relative rounded-md shadow-sm">
                                    <input type="text" id="video_url" name="data[video_url]" value="{{ old('data.video_url', $globalWidget->data['video_url'] ?? '') }}" readonly required class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                    <button type="button" id="btn-sfoglia-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli MP4...</button>
                                </div>
                            </div>
                            <div class="mb-4 flex space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[autoplay]" value="1" {{ old('data.autoplay', $globalWidget->data['autoplay'] ?? 0) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Autoplay (Parte in automatico)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[loop]" value="1" {{ old('data.loop', $globalWidget->data['loop'] ?? 0) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Loop (Ripeti all'infinito)</span>
                                </label>
                            </div>

                        <!-- Mirror Blocks -->
                        @elseif($globalWidget->tipo === 'mirror_blocks')
                            <div class="grid grid-cols-2 gap-4 mb-4 mt-6">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                    <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="">-- Seleziona --</option>
                                        @foreach(\App\Models\Section::all() as $sez)
                                            <option value="{{ $sez->id }}" {{ old('data.source_section_id', $globalWidget->data['source_section_id'] ?? '') == $sez->id ? 'selected' : '' }}>{{ $sez->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=10; $i++)
                                            <option value="{{ $i }}" {{ old('data.limit', $globalWidget->data['limit'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                        <!-- Single Block -->
                        @elseif($globalWidget->tipo === 'single_block')
                            <div class="mb-4 mt-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale)</label>
                                <input type="text" name="data[subtitle]" value="{{ old('data.subtitle', $globalWidget->data['subtitle'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Link al click (Opzionale)</label>
                                <input type="url" name="data[link]" value="{{ old('data.link', $globalWidget->data['link'] ?? '') }}" placeholder="https://..." class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Sfondo / Principale</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_image" name="data[image]" value="{{ old('data.image', $globalWidget->data['image'] ?? '') }}" readonly required placeholder="Immagine dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Foto...</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Sfondo (Opzionale)</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_video" name="data[video]" value="{{ old('data.video', $globalWidget->data['video'] ?? '') }}" readonly placeholder="URL Video (.mp4)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Video...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo Blocco (HEX)</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="data[bg_color]" value="{{ old('data.bg_color', $globalWidget->data['bg_color'] ?? '#ffffff') }}" class="h-10 w-10 border rounded cursor-pointer">
                                        <span class="text-sm text-gray-500">Scegli un colore di sfondo.</span>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Disposizione Contenuto</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="side" {{ (old('data.layout', $globalWidget->data['layout'] ?? 'side') == 'side') ? 'checked' : '' }} class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto a sinistra (Full)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="stacked" {{ (old('data.layout', $globalWidget->data['layout'] ?? '') == 'stacked') ? 'checked' : '' }} class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto sopra (Stacked)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        <!-- Section Grid -->
                        @elseif($globalWidget->tipo === 'section_grid')
                            <div class="grid grid-cols-2 gap-4 mb-4 mt-6">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                    <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="">-- Seleziona --</option>
                                        @foreach(\App\Models\Section::all() as $sez)
                                            <option value="{{ $sez->id }}" {{ old('data.source_section_id', $globalWidget->data['source_section_id'] ?? '') == $sez->id ? 'selected' : '' }}>{{ $sez->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}" {{ old('data.limit', $globalWidget->data['limit'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per Riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3">
                                        @foreach([1, 2, 3, 4] as $col)
                                            <option value="{{ $col }}" {{ old('data.columns', $globalWidget->data['columns'] ?? 3) == $col ? 'selected' : '' }}>{{ $col }} Colonn{{ $col > 1 ? 'e' : 'a' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <!-- Image Text Image -->
                        @elseif($globalWidget->tipo === 'image_text_image')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 mt-6">
                                <!-- Elemento Sinistra -->
                                <div class="bg-gray-50 p-4 rounded shadow-inner border border-gray-200">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Sinistra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_left" name="data[img_left]" value="{{ old('data.img_left', $globalWidget->data['img_left'] ?? '') }}" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_left">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_left" name="data[video_left]" value="{{ old('data.video_left', $globalWidget->data['video_left'] ?? '') }}" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_left">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_left]" value="{{ old('data.link_left', $globalWidget->data['link_left'] ?? '') }}" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>

                                <!-- Elemento Centrale (Testo) -->
                                <div class="bg-gray-50 p-4 rounded shadow-inner border border-gray-200">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Testo Centrale</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Titolo in Evidenza</label>
                                        <input type="text" name="data[center_title]" value="{{ old('data.center_title', $globalWidget->data['center_title'] ?? '') }}" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Sottotitolo / Descrizione</label>
                                        <textarea name="data[center_subtitle]" rows="3" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">{{ old('data.center_subtitle', $globalWidget->data['center_subtitle'] ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Colore Testo (HEX)</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="data[text_color]" value="{{ old('data.text_color', $globalWidget->data['text_color'] ?? '#111827') }}" class="h-8 w-8 border rounded cursor-pointer">
                                        </div>
                                    </div>
                                </div>

                                <!-- Elemento Destra -->
                                <div class="bg-gray-50 p-4 rounded shadow-inner border border-gray-200">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Destra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_right" name="data[img_right]" value="{{ old('data.img_right', $globalWidget->data['img_right'] ?? '') }}" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_right">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_right" name="data[video_right]" value="{{ old('data.video_right', $globalWidget->data['video_right'] ?? '') }}" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_right">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_right]" value="{{ old('data.link_right', $globalWidget->data['link_right'] ?? '') }}" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                        <!-- Booking Search Widget -->
                        @elseif($globalWidget->tipo === 'booking_search')
                            <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner mt-6">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Questo widget mostrerà un motore di ricerca disponibilità. Sarà visibile sul sito pubblico solo se il modulo <strong>Booking</strong> è abilitato.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Testo in Evidenza (Titolo Interno)</label>
                                        <input type="text" name="data[title]" value="{{ old('data.title', $globalWidget->data['title'] ?? '') }}" placeholder="es. Trova la tua struttura perfetta" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo</label>
                                        <input type="text" name="data[subtitle]" value="{{ old('data.subtitle', $globalWidget->data['subtitle'] ?? '') }}" placeholder="es. Inserisci le date per verificare la disponibilità" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-right mt-8 pt-4 border-t">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Salva Modifiche</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
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
                let photoIndex = {{ isset($photos) ? count($photos) : 1 }};

                if (document.getElementById('btn-aggiungi-foto')) {
                    document.getElementById('btn-aggiungi-foto').addEventListener('click', () => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center space-x-2 gallery-row mb-3';
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
