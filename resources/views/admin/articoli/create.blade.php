<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crea Nuovo Articolo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <form action="{{ route('admin.articoli.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Sezione -->
                        <div class="mb-4">
                            <label for="section_id" class="block text-gray-700 text-sm font-bold mb-2">Sezione *</label>
                            <select name="section_id" id="section_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- Seleziona una Sezione --</option>
                                @foreach($sezioni as $sezione)
                                    <option value="{{ $sezione->id }}" {{ old('section_id') == $sezione->id ? 'selected' : '' }}>
                                        {{ $sezione->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Titolo -->
                        <div class="mb-4">
                            <label for="titolo" class="block text-gray-700 text-sm font-bold mb-2">Titolo *</label>
                            <input type="text" name="titolo" id="titolo" value="{{ old('titolo') }}" required autofocus
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('titolo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Sottotitolo -->
                        <div class="mb-4">
                            <label for="sottotitolo" class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo</label>
                            <input type="text" name="sottotitolo" id="sottotitolo" value="{{ old('sottotitolo') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('sottotitolo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- URL Custom (Slug) -->
                        <div class="mb-4">
                            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">URL Personalizzato (Opzionale)</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="Lascia vuoto per autogenerare (es. 15-it)"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Senza spazi, usa i trattini. Es: il-mio-nuovo-articolo</p>
                            @error('slug') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Visibile (Pubblicato) -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('visibile', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Pubblica questo articolo (visibile a tutti)</span>
                            </label>
                            @error('visibile') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Includi Modulo Contatti -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_contact_form" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('has_contact_form') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Includi modulo di contatto alla fine dell'articolo</span>
                            </label>
                            @error('has_contact_form') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Mostra Data -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="mostra_data" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('mostra_data', true) ? 'checked' : '' }}>
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
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descrizione') }}</textarea>
                            @error('descrizione') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Link -->
                        <div class="mb-4">
                            <label for="link" class="block text-gray-700 text-sm font-bold mb-2">Link esterno (URL)</label>
                            <input type="url" name="link" id="link" value="{{ old('link') }}" placeholder="https://..."
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
                                        <input type="text" name="foto" id="foto" value="{{ old('foto') }}" readonly placeholder="Scegli foto..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <button type="button" id="fm-foto-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0 text-sm">
                                            Scegli
                                        </button>
                                    </div>
                                </div>

                                <!-- Video -->
                                <div>
                                    <label for="video" class="block text-gray-700 text-sm font-bold mb-2">Video Principale (MP4)</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="video" id="video" value="{{ old('video') }}" readonly placeholder="Scegli video..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        <button type="button" id="fm-video-button" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0 text-sm">
                                            Scegli
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <label for="allineamento_media" class="block text-gray-700 text-sm font-bold mb-2">Allineamento Media (Foto o Video)</label>
                                <select name="allineamento_media" id="allineamento_media" class="shadow border rounded w-full md:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                    <option value="left" {{ old('allineamento_media') == 'left' ? 'selected' : '' }}>⬅ Sinistra</option>
                                    <option value="center" {{ old('allineamento_media', 'center') == 'center' ? 'selected' : '' }}>↔ Centro</option>
                                    <option value="right" {{ old('allineamento_media') == 'right' ? 'selected' : '' }}>➡ Destra</option>
                                </select>
                            </div>
                        </div>

                        <!-- Allegato -->
                        <div class="mb-8">
                            <label for="allegato" class="block text-gray-700 text-sm font-bold mb-2">Allegato PDF/Word (Opzionale)</label>
                            <div class="flex items-stretch">
                                <input type="text" name="allegato" id="allegato" value="{{ old('allegato') }}" readonly placeholder="Nessun allegato selezionato. Clicca su Scegli..."
                                    class="shadow appearance-none border rounded-l flex-1 py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
                                <button type="button" id="fm-allegato-button" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Scegli file
                                </button>
                            </div>
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
                                    <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}" placeholder="Titolo personalizzato..."
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('seo_title') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_description" class="block text-gray-700 text-sm font-bold mb-2">Descrizione/Riassunto Social (max 160 caratteri ideali)</label>
                                    <textarea name="seo_description" id="seo_description" rows="3"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('seo_description') }}</textarea>
                                    @error('seo_description') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="seo_image" class="block text-gray-700 text-sm font-bold mb-2">Immagine Anteprima Social</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="seo_image" id="seo_image" value="{{ old('seo_image') }}" readonly placeholder="Seleziona Immagine da File Manager..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <button type="button" id="fm-seo-image-button" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                            Scegli Immagine
                                        </button>
                                        <button type="button" id="fm-seo-image-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @error('seo_image') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pb-4">
                            <x-primary-button>
                                {{ __('Salva Articolo') }}
                            </x-primary-button>
                            <a href="{{ route('admin.articoli.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                                Annulla
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            let editorInstance;
            let fmActiveTarget = 'editor';
            
            ClassicEditor
                .create( document.querySelector( '#descrizione' ) )
                .then( editor => {
                    editorInstance = editor;
                } )
                .catch( error => {
                    console.error( error );
                } );

            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('fm-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'editor';
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-foto-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'foto';
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-allegato-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'allegato';
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });
                
                document.getElementById('fm-seo-image-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'seo_image';
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });

                document.getElementById('fm-seo-image-clear').addEventListener('click', (event) => {
                    event.preventDefault();
                    document.getElementById('seo_image').value = '';
                });

                document.getElementById('fm-video-button').addEventListener('click', (event) => {
                    event.preventDefault();
                    fmActiveTarget = 'video';
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });
            });

            // Callback function expected by FileManager
             function fmSetLink($url) {
                const baseUrl = '{{ config('app.url') }}';
                const relativeUrl = $url.replace(baseUrl, '');

                if (fmActiveTarget === 'editor') {
                    if(editorInstance) {
                        editorInstance.model.change( writer => {
                            const imageElement = writer.createElement( 'imageBlock', {
                                src: $url
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
                }
            }
        </script>
    @endpush
</x-app-layout>
