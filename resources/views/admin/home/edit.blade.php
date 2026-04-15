<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Costruzione Home Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Libreria Widget Globali -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-4 bg-gray-50 border-b font-bold text-gray-700 flex justify-between items-center">
                            <span>Libreria Widget Globali</span>
                            <a href="{{ route('admin.global-widgets.create') }}" class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded hover:bg-indigo-200">+ Nuovo</a>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-500 mb-4">Trascina i widget da qui verso l'area della Home Page a destra.</p>
                            
                            <div id="libreria-widget" class="space-y-3 min-h-[100px] bg-gray-50 p-3 rounded border border-dashed border-gray-300">
                                @foreach($globalWidgets as $widget)
                                    @php $isShop = str_starts_with($widget->tipo, 'shop_'); @endphp
                                    <div class="widget-item bg-white p-3 rounded shadow-sm border border-gray-200 cursor-move hover:border-indigo-300 hover:shadow flex items-center justify-between" data-id="{{ $widget->id }}">
                                        <div>
                                            <span class="inline-block {{ $isShop ? 'bg-orange-100 text-orange-800' : 'bg-indigo-100 text-indigo-800' }} text-[10px] px-1.5 py-0.5 rounded uppercase font-bold mr-2">{{ $widget->tipo }}</span>
                                            <strong class="text-gray-800 text-sm">{{ $widget->titolo }}</strong>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout Home Page -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-indigo-100 relative">
                        <div class="p-4 bg-indigo-50 border-b border-indigo-100 font-bold text-indigo-800 flex justify-between items-center">
                            <span>Layout Attuale Home Page</span>
                            <span class="text-xs font-normal text-indigo-600">Ordina trascinando in su o in giù</span>
                        </div>
                        <div class="p-6 bg-gray-50/50">
                            <form id="home-form" action="{{ route('admin.home.update') }}" method="POST">
                                @csrf
                                <div id="home-layout" class="space-y-4 min-h-[300px] p-4 bg-white rounded-lg shadow-inner border border-dashed border-gray-300 relative">
                                    @if($homeBlocks->isEmpty())
                                        <div id="empty-state" class="absolute inset-0 flex items-center justify-center text-gray-400 font-medium pointer-events-none">
                                            Trascina i widget qui dentro
                                        </div>
                                    @endif
                                    
                                    @foreach($homeBlocks as $block)
                                        <div class="widget-item relative bg-indigo-50 p-4 rounded-lg shadow border border-indigo-200 cursor-move flex items-center justify-between" data-id="{{ $block->global_widget_id }}">
                                            <div class="flex items-center">
                                                <div class="mr-4 text-indigo-300 cursor-move">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                                </div>
                                            <div class="flex-grow pr-8">
                                                <div class="mb-3 flex items-center justify-between border-b border-indigo-100 pb-2">
                                                    <div>
                                                        @php $isShop = $block->globalWidget && str_starts_with($block->globalWidget->tipo, 'shop_'); @endphp
                                                        <span class="inline-block {{ $isShop ? 'bg-orange-200 text-orange-900' : 'bg-indigo-200 text-indigo-900' }} text-xs px-2 py-0.5 rounded uppercase font-bold mr-2">{{ $block->globalWidget->tipo ?? 'Eliminato' }}</span>
                                                        <strong class="text-gray-900">{{ $block->globalWidget->titolo ?? 'Widget Non Trovato' }}</strong>
                                                    </div>
                                                </div>
                                                
                                                <!-- Anteprima Contenuto Reale -->
                                                <div class="pointer-events-none opacity-90 scale-[0.85] origin-top-left max-h-[400px] overflow-hidden bg-white p-4 rounded border">
                                                    @if($block->globalWidget)
                                                        @php $widget = $block->globalWidget; @endphp
                                                        @if($widget->tipo === 'gallery')
                                                            @include('public.partials.widgets.gallery', ['widget' => $widget, 'adminPreview' => true])
                                                        @elseif($widget->tipo === 'video')
                                                            @include('public.partials.widgets.video', ['widget' => $widget, 'adminPreview' => true])
                                                        @elseif($widget->tipo === 'mirror_blocks')
                                                            @include('public.partials.widgets.mirror', ['widget' => $widget, 'adminPreview' => true])
                                                        @elseif($widget->tipo === 'shop_collection')
                                                            @include('public.partials.widgets.shop_collection', ['widget' => $widget, 'adminPreview' => true])
                                                        @elseif($widget->tipo === 'shop_featured_products')
                                                            @include('public.partials.widgets.shop_featured_products', ['widget' => $widget, 'adminPreview' => true])
                                                        @elseif($widget->tipo === 'shop_brands')
                                                            @include('public.partials.widgets.shop_brands', ['widget' => $widget, 'adminPreview' => true])
                                                        @else
                                                            <div class="p-4 bg-gray-50 text-gray-400 text-xs italic">Anteprima non disponibile per questo tipo di widget</div>
                                                        @endif
                                                    @else
                                                        <p class="text-red-500">Widget originale cancellato o mancante.</p>
                                                    @endif
                                                </div>
                                            </div>
                                            </div>
                                            <!-- Hidden input per il form -->
                                            <input type="hidden" name="blocks[]" value="{{ $block->global_widget_id }}">
                                            
                                            <button type="button" class="rimuovi-widget text-red-500 hover:text-red-700 bg-white rounded-full p-2 shadow border absolute top-4 right-4 z-10" title="Rimuovi dalla Home">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-8 flex flex-col md:flex-row justify-end gap-4 bg-gray-50 -mx-6 -mb-6 p-4 border-t border-gray-200">
                                    <button type="button" id="btn-preview" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-lg shadow-sm text-lg w-full md:w-auto flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Anteprima Bozza
                                    </button>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg w-full md:w-auto flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Salva Nuova Home Page
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Impostazioni SEO Globali Home Page -->
                <div class="w-full mt-8">
                    <div class="bg-gray-50 border border-gray-300 rounded-lg shadow-sm">
                        <div class="p-4 bg-gray-200 border-b border-gray-300 font-bold text-gray-700 flex justify-between items-center rounded-t-lg">
                            <span>SEO & Condivisione Social (Home Page / Impostazioni Globali)</span>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-6">Queste informazioni verranno utilizzate come predefinite per la Home Page e come "salvavita" per tutte quelle pagine/articoli che non hanno informazioni SEO specifiche.</p>
                            
                            <form id="seo-form">
                                <div class="mb-4">
                                    <label for="home_seo_title" class="block text-gray-700 text-sm font-bold mb-2">Titolo Generale Sito (Meta Title)</label>
                                    <input type="text" name="home_seo_title" id="home_seo_title" value="{{ $settings['home_seo_title'] ?? '' }}" placeholder="Es: Nome Azienda - Il miglior servizio..."
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="home_seo_description" class="block text-gray-700 text-sm font-bold mb-2">Descrizione Generale Sito (Meta Description)</label>
                                    <textarea name="home_seo_description" id="home_seo_description" rows="3" placeholder="Siamo un'azienda leader nel settore..."
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $settings['home_seo_description'] ?? '' }}</textarea>
                                </div>
                                <div class="mb-6">
                                    <label for="home_seo_image" class="block text-gray-700 text-sm font-bold mb-2">Immagine Anteprima Social Globale (es. Logo Grande)</label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="home_seo_image" id="home_seo_image" value="{{ $settings['home_seo_image'] ?? '' }}" readonly placeholder="Scegli Immagine dal File Manager..."
                                            class="shadow appearance-none border rounded-l flex-1 py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <button type="button" id="fm-seo-image-button" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-r shadow whitespace-nowrap flex-shrink-0">
                                            Scegli Immagine
                                        </button>
                                        <button type="button" id="fm-seo-image-clear" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 ml-2 rounded shadow" title="Rimuovi">X</button>
                                    </div>
                                    @if(!empty($settings['home_seo_image']))
                                        <div class="mt-2 text-sm text-gray-600" id="seo-image-preview-container">
                                            <img src="{{ $settings['home_seo_image'] }}" id="seo-image-preview" class="h-20 w-auto object-cover mt-1 border rounded shadow-sm">
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-end">
                                    <span id="seo-save-msg" class="text-green-600 font-bold mr-4 hidden">Salvato con successo!</span>
                                    <button type="button" id="btn-save-seo" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">
                                        Salva Opzioni SEO
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <!-- SortableJS per Drag & Drop -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const libContainer = document.getElementById('libreria-widget');
                const homeContainer = document.getElementById('home-layout');
                const emptyState = document.getElementById('empty-state');

                function updateFormInputs() {
                    // Rimuove i vecchi input nascosti
                    homeContainer.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
                    
                    const items = homeContainer.querySelectorAll('.widget-item');
                    if (items.length > 0 && emptyState) {
                        emptyState.style.display = 'none';
                    } else if (items.length === 0 && emptyState) {
                        emptyState.style.display = 'flex';
                    }

                    items.forEach((item, index) => {
                        const widgetId = item.getAttribute('data-id');
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'blocks[]';
                        input.value = widgetId;
                        item.appendChild(input);
                        
                        // Assicurarsi che ci sia il pulsante di rimozione e la struttura di anteprima se cloniamo dalla libreria
                        if (!item.querySelector('.rimuovi-widget')) {
                           item.classList.replace('bg-white', 'bg-indigo-50');
                           item.classList.replace('p-3', 'p-4');
                           
                           // Dobbiamo ricaricare la pagina per fargli compilare la VERA preview tramite Blade,
                           // ma per il momento visivo immediato mettiamo un blocco segnaposto
                           const originalContent = item.innerHTML;
                           item.innerHTML = `
                               <div class="flex items-start w-full relative">
                                   <div class="mr-4 text-indigo-300 cursor-move pt-2 mt-4">
                                       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                   </div>
                                   <div class="flex-grow pr-8 w-full">
                                        <div class="mb-3 flex items-center justify-between border-b border-indigo-100 pb-2">
                                            ${originalContent}
                                        </div>
                                        <div class="bg-indigo-100/50 p-8 rounded border-2 border-dashed border-indigo-200 text-center animate-pulse">
                                            <span class="text-indigo-400 font-medium">Salva per generare l'anteprima reale...</span>
                                        </div>
                                   </div>
                               </div>
                           `;
                           
                           // Add remove button html
                           const removeBtnHtml = `<button type="button" class="rimuovi-widget text-red-500 hover:text-red-700 bg-white rounded-full p-2 shadow border absolute top-4 right-4 z-10" title="Rimuovi dalla Home"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>`;
                           item.insertAdjacentHTML('beforeend', removeBtnHtml);
                        }
                    });
                }

                // Inizializza Sortable per la Libreria (Clonazione)
                new Sortable(libContainer, {
                    group: {
                        name: 'shared',
                        pull: 'clone',
                        put: false // Non si può trascinare DALLA home ALLA libreria
                    },
                    animation: 150,
                    sort: false // I bottoni della libreria rimangono immobili
                });

                // Inizializza Sortable per il Layout Home
                new Sortable(homeContainer, {
                    group: 'shared',
                    animation: 150,
                    ghostClass: 'bg-indigo-100',
                    onAdd: function (evt) {
                        updateFormInputs();
                    },
                    onUpdate: function (evt) {
                        updateFormInputs();
                    }
                });

                // Gestione rimozione widget dalla home
                homeContainer.addEventListener('click', function(e) {
                    if(e.target.closest('.rimuovi-widget')) {
                        e.target.closest('.widget-item').remove();
                        updateFormInputs();
                    }
                });

                // Gestione Anteprima Bozza
                const btnPreview = document.getElementById('btn-preview');
                if(btnPreview) {
                    btnPreview.addEventListener('click', function(e) {
                        e.preventDefault();
                        const blocks = Array.from(homeContainer.querySelectorAll('input[name="blocks[]"]')).map(el => el.value);
                        if(blocks.length === 0) {
                            alert('Aggiungi almeno un blocco per vedere l\'anteprima.');
                            return;
                        }
                        const url = "{{ route('admin.home.preview') }}?blocks=" + blocks.join(',');
                        window.open(url, '_blank');
                    });
                }

                // Gestione File Manager SEO Image
                let fmActiveTarget = null;
                const fmBtn = document.getElementById('fm-seo-image-button');
                const fmClear = document.getElementById('fm-seo-image-clear');
                const seoImageInput = document.getElementById('home_seo_image');
                
                if (fmBtn) {
                    fmBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        fmActiveTarget = 'home_seo_image';
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }
                
                if (fmClear) {
                    fmClear.addEventListener('click', function(e) {
                        e.preventDefault();
                        seoImageInput.value = '';
                        const preview = document.getElementById('seo-image-preview-container');
                        if(preview) preview.style.display = 'none';
                    });
                }

                window.fmSetLink = function($url) {
                    if (fmActiveTarget === 'home_seo_image') {
                        seoImageInput.value = $url;
                        // Opzionale: update preview immediato
                        let previewCont = document.getElementById('seo-image-preview-container');
                        if (!previewCont) {
                            previewCont = document.createElement('div');
                            previewCont.id = 'seo-image-preview-container';
                            previewCont.className = 'mt-2 text-sm text-gray-600';
                            previewCont.innerHTML = `<img src="${$url}" id="seo-image-preview" class="h-20 w-auto object-cover mt-1 border rounded shadow-sm">`;
                            seoImageInput.closest('.mb-6').appendChild(previewCont);
                        } else {
                            previewCont.style.display = 'block';
                            document.getElementById('seo-image-preview').src = $url;
                        }
                    }
                };

                // Salvataggio AJAX SEO Form
                const btnSaveSeo = document.getElementById('btn-save-seo');
                if (btnSaveSeo) {
                    btnSaveSeo.addEventListener('click', function() {
                        const form = document.getElementById('seo-form');
                        const formData = new FormData(form);
                        const msg = document.getElementById('seo-save-msg');
                        const originalText = btnSaveSeo.innerText;
                        
                        btnSaveSeo.innerText = 'Salvataggio...';
                        btnSaveSeo.disabled = true;

                        fetch('{{ route("admin.home.updateSeo") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                msg.classList.remove('hidden');
                                setTimeout(() => msg.classList.add('hidden'), 3000);
                            } else {
                                alert('Errore nel salvataggio');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Errore di connessione durante il salvataggio.');
                        })
                        .finally(() => {
                            btnSaveSeo.innerText = originalText;
                            btnSaveSeo.disabled = false;
                        });
                    });
                }

                // Inizializzazione controlli
                updateFormInputs();
            });
        </script>
    @endpush
</x-app-layout>
