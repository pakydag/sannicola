<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica Sezione') }}: {{ $sezione->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Attenzione!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.sezioni.update', $sezione) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($sezione->modulo)
                            <input type="hidden" name="modulo" value="{{ $sezione->modulo }}">
                            <input type="hidden" name="tipo" value="{{ $sezione->tipo }}">
                            <input type="hidden" name="slug" value="{{ $sezione->slug }}">
                            
                            <div class="mb-6 p-4 bg-gray-50 border-l-4 border-gray-400 rounded">
                                <p class="text-sm text-gray-700">Questa è una <strong>Sezione di Sistema</strong> (Modulo {{ strtoupper($sezione->modulo) }}). Puoi cambiarne il nome nel menu, l'ordine di apparizione e la visibilità, ma non l'URL o il tipo di contenuto.</p>
                            </div>
                        @endif

                        <!-- Nome -->
                        <div class="mb-4">
                            <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome Sezione (Es. Chi Siamo) *</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome', $sezione->nome) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('nome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Sottotitolo -->
                        <div class="mb-4">
                            <label for="sottotitolo" class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale)</label>
                            <input type="text" name="sottotitolo" id="sottotitolo" value="{{ old('sottotitolo', $sezione->sottotitolo) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Inserisci un breve testo descrittivo...">
                            @error('sottotitolo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(!$sezione->modulo)
                            <!-- URL Custom (Slug) -->
                            <div class="mb-4">
                                <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">URL Personalizzato (Opzionale)</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $sezione->slug) }}" placeholder="Lascia vuoto per autogenerare (es. 5-it)"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <p class="text-xs text-gray-500 mt-1">Senza spazi, usa i trattini. Es: chi-siamo</p>
                                @error('slug') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Ordine -->
                        <div class="mb-4">
                            <label for="ordine" class="block text-gray-700 text-sm font-bold mb-2">Ordine di Pubblicazione</label>
                            <input type="number" name="ordine" id="ordine" value="{{ old('ordine', $sezione->ordine) }}" required
                                class="shadow appearance-none border rounded w-full md:w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Numeri più bassi verranno mostrati per primi (0, 1, 2...).</p>
                            @error('ordine') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(!$sezione->modulo)
                            <!-- Tipo di Sezione -->
                            <div class="mb-4">
                                <span class="block text-gray-700 text-sm font-bold mb-2">Comportamento della Sezione *</span>
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="tipo" value="pagina" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ old('tipo', $sezione->tipo) == 'pagina' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700"><strong>Pagina</strong> (Mostra solo il testo, nessun articolo)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="tipo" value="archivio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {{ old('tipo', $sezione->tipo) == 'archivio' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700"><strong>Archivio</strong> (Mostra l'elenco degli articoli)</span>
                                    </label>
                                </div>
                                @error('tipo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Visibilità Checkboxes -->
                        <div class="mb-4 bg-indigo-50 p-4 rounded border border-indigo-100 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('visibile', $sezione->visibile) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-bold text-gray-700">Pubblicata (Visibile)</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="mostra_nel_menu" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('mostra_nel_menu', $sezione->mostra_nel_menu ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Mostra nel Menu (Header)</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="mostra_nel_footer" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('mostra_nel_footer', $sezione->mostra_nel_footer ?? false) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Mostra nel Footer</span>
                            </label>
                        </div>

                        <!-- Menu a tendina Checkbox (Condizionale) -->
                        <div class="mb-4" id="dropdown-wrapper">
                            <label class="flex items-center">
                                <input type="checkbox" name="menu_a_tendina" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('menu_a_tendina', $sezione->menu_a_tendina) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Mostra il sottomenù a tendina nel menù di navigazione (per categorie archivio)</span>
                            </label>
                        </div>

                        <!-- Colonne Griglia (Condizionale) -->
                        <div class="mb-6" id="grid-columns-wrapper">
                            <label for="colonne_griglia" class="block text-gray-700 text-sm font-bold mb-2">Numero di articoli per riga (Lato Pubblico)</label>
                            <select name="colonne_griglia" id="colonne_griglia" class="shadow border rounded w-full md:w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1" {{ old('colonne_griglia', $sezione->colonne_griglia) == 1 ? 'selected' : '' }}>1 Articolo (Lista singola)</option>
                                <option value="2" {{ old('colonne_griglia', $sezione->colonne_griglia) == 2 ? 'selected' : '' }}>2 Articoli affiancati</option>
                                <option value="3" {{ old('colonne_griglia', $sezione->colonne_griglia) == 3 ? 'selected' : '' }}>3 Articoli affiancati</option>
                                <option value="4" {{ old('colonne_griglia', $sezione->colonne_griglia) == 4 ? 'selected' : '' }}>4 Articoli affiancati</option>
                                <option value="6" {{ old('colonne_griglia', $sezione->colonne_griglia) == 6 ? 'selected' : '' }}>6 Articoli affiancati</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Scegli come disporre le anteprime degli articoli se questa sezione è un Archivio.</p>
                            @error('colonne_griglia') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(!$sezione->modulo)
                            <!-- Contenuto testuale/HTML -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="contenuto" class="block text-gray-700 text-sm font-bold">Contenuto (Testo o HTML)</label>
                                    <button type="button" id="fm-button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold py-1 px-3 rounded inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Inserisci Immagine dal File Manager
                                    </button>
                                </div>
                                <textarea name="contenuto" id="contenuto" rows="8"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono text-sm">{{ old('contenuto', $sezione->contenuto) }}</textarea>
                                @error('contenuto') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div class="hidden">
                                <textarea name="contenuto" id="contenuto">{{ $sezione->contenuto }}</textarea>
                            </div>
                        @endif

                        <!-- Immagine di Copertina -->
                        <div class="mb-8 mt-4">
                            <label for="immagine" class="block text-gray-700 text-sm font-bold mb-2">Immagine di Copertina (Header Sezione)</label>
                            <div class="flex items-stretch">
                                <input type="text" name="immagine" id="immagine" value="{{ old('immagine', $sezione->immagine) }}" readonly placeholder="Seleziona Immagine da File Manager..."
                                    class="shadow appearance-none border rounded-l flex-1 py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <button type="button" id="fm-immagine-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                    Scegli Immagine
                                </button>
                                <button type="button" id="fm-immagine-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                            </div>
                            @if($sezione->immagine)
                                <div class="mt-2 text-sm text-gray-600">
                                    <img src="{{ asset($sezione->immagine) }}" class="h-20 w-auto object-cover mt-1 border rounded shadow-sm">
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">Questa immagine verrà visualizzata in alto nella pagina della sezione e negli articoli ad essa collegati.</p>
                            @error('immagine') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Foto Principale + Allineamento -->
                        <div class="mb-8 bg-gray-50 p-4 rounded border border-gray-200">
                            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Foto Principale e Allineamento</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto -->
                                <div>
                                    <label for="foto" class="block text-gray-700 text-sm font-bold mb-2">Foto</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="foto" id="foto" value="{{ old('foto', $sezione->foto) }}" readonly placeholder="Scegli foto..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <button type="button" id="fm-foto-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0 text-sm">
                                            Scegli
                                        </button>
                                        <button type="button" id="fm-foto-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if($sezione->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset($sezione->foto) }}" class="h-20 w-auto object-cover rounded border shadow-sm border-gray-200">
                                        </div>
                                    @endif
                                </div>

                                <!-- Allineamento -->
                                <div>
                                    <label for="allineamento_media" class="block text-gray-700 text-sm font-bold mb-2">Allineamento Foto</label>
                                    <select name="allineamento_media" id="allineamento_media" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <option value="left" {{ old('allineamento_media', $sezione->allineamento_media) == 'left' ? 'selected' : '' }}>⬅ Sinistra</option>
                                        <option value="center" {{ old('allineamento_media', $sezione->allineamento_media ?? 'center') == 'center' ? 'selected' : '' }}>↔ Centro</option>
                                        <option value="right" {{ old('allineamento_media', $sezione->allineamento_media) == 'right' ? 'selected' : '' }}>➡ Destra</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Scegli come allineare la foto rispetto al testo della sezione.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ======== Pannello SEO & Condivisione ======== -->
                        <div class="mt-10 mb-8 border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                            <div class="bg-gray-200 px-4 py-3 border-b border-gray-300">
                                <h3 class="font-bold text-gray-700">SEO & Condivisione Social</h3>
                                <p class="text-xs text-gray-500 mt-1">Imposta come vuoi che questa sezione appaia su Google, Facebook, WhatsApp ecc. Se lasciati vuoti, il sistema userà titolo e descrizione automatici.</p>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <label for="seo_title" class="block text-gray-700 text-sm font-bold mb-2">Titolo per Motori di Ricerca/Social</label>
                                    <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $sezione->seo_title) }}" placeholder="Titolo personalizzato..."
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('seo_title') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_description" class="block text-gray-700 text-sm font-bold mb-2">Descrizione/Riassunto Social (max 160 caratteri ideali)</label>
                                    <textarea name="seo_description" id="seo_description" rows="3"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('seo_description', $sezione->seo_description) }}</textarea>
                                    @error('seo_description') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_image" class="block text-gray-700 text-sm font-bold mb-2">Immagine Anteprima Social</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="seo_image" id="seo_image" value="{{ old('seo_image', $sezione->seo_image) }}" readonly placeholder="Seleziona Immagine da File Manager..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <button type="button" id="fm-seo-image-button" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                            Scegli Immagine
                                        </button>
                                        <button type="button" id="fm-seo-image-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if($sezione->seo_image)
                                        <div class="mt-2 text-sm text-gray-600">
                                            <img src="{{ asset($sezione->seo_image) }}" class="h-20 w-auto object-cover mt-1 border rounded shadow-sm">
                                        </div>
                                    @endif
                                    @error('seo_image') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pb-4">
                            <x-primary-button>
                                {{ __('Aggiorna Sezione') }}
                            </x-primary-button>
                            <a href="{{ route('admin.sezioni.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                                Annulla
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/super-build/ckeditor.js"></script>
        <script>
            let editorInstance;
            let fmActiveTarget = 'editor';
            
            CKEDITOR.ClassicEditor
                .create( document.querySelector( '#contenuto' ), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'alignment', '|',
                            'link', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    language: 'it',
                    alignment: {
                        options: [ 'left', 'center', 'right', 'justify' ]
                    }
                } )
                .then( editor => {
                    editorInstance = editor;
                } )
                .catch( error => {
                    console.error( error );
                } );

            document.addEventListener("DOMContentLoaded", function() {
                const fmButton = document.getElementById('fm-button');
                if (fmButton) {
                    fmButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveTarget = 'editor';
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const fmSeoImageButton = document.getElementById('fm-seo-image-button');
                if (fmSeoImageButton) {
                    fmSeoImageButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveTarget = 'seo_image';
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const fmImmagineButton = document.getElementById('fm-immagine-button');
                if (fmImmagineButton) {
                    fmImmagineButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveTarget = 'immagine';
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const fmFotoButton = document.getElementById('fm-foto-button');
                if (fmFotoButton) {
                    fmFotoButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveTarget = 'foto';
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const fmImmagineClear = document.getElementById('fm-immagine-clear');
                if (fmImmagineClear) {
                    fmImmagineClear.addEventListener('click', (event) => {
                        event.preventDefault();
                        document.getElementById('immagine').value = '';
                    });
                }

                const fmFotoClear = document.getElementById('fm-foto-clear');
                if (fmFotoClear) {
                    fmFotoClear.addEventListener('click', (event) => {
                        event.preventDefault();
                        document.getElementById('foto').value = '';
                    });
                }

                const fmSeoImageClear = document.getElementById('fm-seo-image-clear');
                if (fmSeoImageClear) {
                    fmSeoImageClear.addEventListener('click', (event) => {
                        event.preventDefault();
                        document.getElementById('seo_image').value = '';
                    });
                }
            });

            // Callback function expected by FileManager
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

                if(fmActiveTarget === 'editor') {
                    if(editorInstance) {
                        editorInstance.model.change( writer => {
                            const imageElement = writer.createElement( 'imageBlock', {
                                src: $url
                            } );
                            editorInstance.model.insertContent( imageElement, editorInstance.model.document.selection );
                        } );
                    }
                } else if(fmActiveTarget === 'seo_image') {
                    document.getElementById('seo_image').value = relativeUrl;
                } else if(fmActiveTarget === 'immagine') {
                    document.getElementById('immagine').value = relativeUrl;
                } else if(fmActiveTarget === 'foto') {
                    document.getElementById('foto').value = relativeUrl;
                }
            }

            // Logica per mostrare/nascondere opzioni Archivio
            document.addEventListener("DOMContentLoaded", function () {
                const radioPagina = document.querySelector('input[name="tipo"][value="pagina"]');
                const radioArchivio = document.querySelector('input[name="tipo"][value="archivio"]');
                const dropdownWrapper = document.getElementById('dropdown-wrapper');
                const gridColumnsWrapper = document.getElementById('grid-columns-wrapper');

                function toggleArchivioOptions() {
                    if (radioArchivio.checked) {
                        dropdownWrapper.style.display = 'block';
                        gridColumnsWrapper.style.display = 'block';
                    } else {
                        dropdownWrapper.style.display = 'none';
                        gridColumnsWrapper.style.display = 'none';
                        // Optionally uncheck when hiding
                        dropdownWrapper.querySelector('input[type="checkbox"]').checked = false;
                    }
                }

                radioPagina.addEventListener('change', toggleArchivioOptions);
                radioArchivio.addEventListener('change', toggleArchivioOptions);
                
                // Run on initial load
                toggleArchivioOptions();
            });
        </script>
    @endpush
</x-app-layout>
