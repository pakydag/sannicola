@php
    if (!isset($displayCategories)) {
        $macros = \App\Models\ShopCategory::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('visibile', true)->orderBy('ordine');
            }, 'children.children' => function($q) {
                $q->where('visibile', true)->orderBy('ordine');
            }])
            ->where('visibile', true)
            ->orderBy('ordine')
            ->get();

        $displayCategories = $macros;
        if ($macros->count() === 1) {
            $singleMacro = $macros->first();
            if ($singleMacro->children->count() > 0) {
                $displayCategories = $singleMacro->children;
                if ($displayCategories->count() === 1) {
                    $singleCat = $displayCategories->first();
                    if ($singleCat->children->count() > 0) {
                        $displayCategories = $singleCat->children;
                    }
                }
            }
        }
    }

    if (!isset($marche)) {
        $marche = \App\Models\ShopBrand::where('visibile', true)->orderBy('ordine')->get();
    }

    $isOffertaActive = request('in_offerta') || request('offerta');
    $offertaUrl = $isOffertaActive 
        ? route('public.shop.index', request()->except(['in_offerta', 'offerta'])) 
        : route('public.shop.index', array_merge(request()->all(), ['in_offerta' => 1]));
@endphp

<div class="sticky top-20 z-40 py-4 mb-6 lg:mb-8 bg-transparent backdrop-blur-md sm:px-6 sm:rounded-2xl border-0 shadow-none transition-all duration-200 max-w-7xl mx-auto" style="position: -webkit-sticky; position: sticky; top: 80px; z-index: 40;" x-data="{ openCat: false, openMarche: false }">
    <!-- Barra di Ricerca Principale -->
    <form action="{{ route('public.shop.index') }}" method="GET" class="relative max-w-2xl mx-6 sm:mx-auto">
        @if(request('marca'))
            <input type="hidden" name="marca" value="{{ request('marca') }}">
        @endif
        @if(request('in_offerta'))
            <input type="hidden" name="in_offerta" value="1">
        @endif
        <div class="relative flex items-center bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:border-primary focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 transition-all">
            <div class="pl-3.5 sm:pl-4 text-gray-400 pointer-events-none flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                   name="q" 
                   value="{{ request('q') ?? request('search') }}" 
                   placeholder="Cerca un prodotto" 
                   class="w-full py-3 sm:py-3.5 px-2.5 sm:px-4 bg-transparent border-0 text-xs sm:text-sm md:text-base focus:ring-0 focus:outline-none text-gray-800 placeholder-gray-400">
            @if(request('q') || request('search'))
                <a href="{{ route('public.shop.index', request()->except(['q', 'search'])) }}" class="px-2 sm:px-3 text-xs sm:text-sm text-gray-400 hover:text-red-500 font-medium whitespace-nowrap transition-colors" title="Azzera ricerca">
                    ✕
                </a>
            @endif
            <button type="submit" class="mr-1 sm:mr-2 bg-primary hover:bg-secondary text-white text-xs sm:text-sm font-bold uppercase tracking-wider px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all shadow-sm flex items-center gap-1.5 flex-shrink-0">
                <span>Cerca</span>
            </button>
        </div>
    </form>

    <!-- Barra Controlli Sticky Solo Mobile (Categorie Dropdown, Offerte, Marche, Reset) -->
    <div class="relative flex lg:hidden mt-2 pt-2 items-center justify-center gap-2 max-w-2xl mx-auto text-xs">
        <div class="flex items-center justify-center gap-2 flex-wrap w-full">
            <!-- Menù a Tendina Categorie (inizialmente chiuso, si apre al click) -->
            <div class="relative">
                <button type="button" 
                        @click="openCat = !openCat; openMarche = false" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-semibold transition-all border shadow-sm cursor-pointer {{ isset($categoria) ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 border-gray-200 hover:border-primary hover:text-primary' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span class="max-w-[120px] sm:max-w-none truncate">{{ isset($categoria) ? $categoria->nome : 'Categorie' }}</span>
                    <svg class="w-3 h-3 transform transition-transform duration-200" :class="{ 'rotate-180': openCat }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Tendina Categorie -->
                <div x-show="openCat" 
                     x-cloak 
                     @click.outside="openCat = false" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute left-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 p-2 z-50 max-h-[65vh] overflow-y-auto">
                    <div class="px-3 py-2 border-b border-gray-100 flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <span>Tutte le Categorie</span>
                        <button type="button" @click="openCat = false" class="text-gray-400 hover:text-gray-600 text-sm p-1">✕</button>
                    </div>
                    <div class="py-1 space-y-0.5">
                        <a href="{{ route('public.shop.index') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 hover:text-primary transition-colors">
                            Tutte le Collezioni / Catalogo
                        </a>
                        @foreach($displayCategories as $cat)
                            @php
                                $isCatOpen = isset($categoria) && ($categoria->id == $cat->id || $categoria->parent_id == $cat->id);
                            @endphp
                            <div x-data="{ subOpen: {{ $isCatOpen ? 'true' : 'false' }} }">
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-gray-50 transition-colors {{ (isset($categoria) && $categoria->id == $cat->id) ? 'bg-gray-100 text-primary font-bold' : 'text-gray-800' }}">
                                    <a href="{{ route('public.shop.categoria', $cat->slug) }}" class="flex-grow text-xs font-semibold hover:text-primary">
                                        {{ $cat->nome }}
                                    </a>
                                    @if($cat->children->count() > 0)
                                        <button type="button" @click.stop="subOpen = !subOpen" class="p-1 text-gray-400 hover:text-primary focus:outline-none">
                                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @if($cat->children->count() > 0)
                                    <div x-show="subOpen" x-cloak class="pl-3 pr-1 py-1 space-y-0.5 bg-gray-50/70 rounded-xl my-0.5 border border-gray-100/60">
                                        @foreach($cat->children as $child)
                                            <a href="{{ route('public.shop.categoria', $child->slug) }}" class="block px-3 py-1.5 rounded-lg text-xs {{ (isset($categoria) && $categoria->id == $child->id) ? 'text-primary font-bold bg-white shadow-xs' : 'text-gray-600 hover:text-primary' }}">
                                                {{ $child->nome }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pulsante In Offerta (Sempre visibile in sticky) -->
            <a href="{{ $offertaUrl }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold transition-all border shadow-sm cursor-pointer {{ $isOffertaActive ? 'bg-red-600 text-white border-red-600 ring-2 ring-red-300' : 'bg-amber-50 text-amber-900 border-amber-300 hover:bg-amber-100' }}"
               title="{{ $isOffertaActive ? 'Rimuovi filtro offerte' : 'Mostra solo prodotti in offerta' }}">
                <svg class="w-3.5 h-3.5 {{ $isOffertaActive ? 'text-white' : 'text-amber-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>In Offerta</span>
                @if($isOffertaActive)
                    <span class="ml-1 text-[10px] bg-white/25 px-1.5 py-0.2 rounded-full">✕</span>
                @endif
            </a>

            <!-- Menù a Tendina Marche -->
            @if($marche->count() > 0)
                <div class="relative">
                    <button type="button" 
                            @click="openMarche = !openMarche; openCat = false" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-semibold transition-all border shadow-sm cursor-pointer {{ request('marca') ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 border-gray-200 hover:border-primary hover:text-primary' }}">
                        <span class="max-w-[100px] sm:max-w-none truncate">{{ request('marca') ? 'Marca: ' . ($marche->firstWhere('slug', request('marca'))->nome ?? request('marca')) : 'Marche' }}</span>
                        <svg class="w-3 h-3 transform transition-transform duration-200" :class="{ 'rotate-180': openMarche }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openMarche" 
                         x-cloak 
                         @click.outside="openMarche = false" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 p-2 z-50 max-h-72 overflow-y-auto">
                        <div class="px-3 py-2 border-b border-gray-100 flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <span>Filtra per Marca</span>
                            <button type="button" @click="openMarche = false" class="text-gray-400 hover:text-gray-600 text-sm p-1">✕</button>
                        </div>
                        <div class="py-1 space-y-0.5">
                            @if(request('marca'))
                                <a href="{{ route('public.shop.index', request()->except('marca')) }}" class="block px-3 py-2 rounded-xl text-xs font-medium text-red-500 hover:bg-red-50">
                                    ✕ Rimuovi filtro marca
                                </a>
                            @endif
                            @foreach($marche as $m)
                                <a href="{{ route('public.shop.index', array_merge(request()->all(), ['marca' => $m->slug])) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request('marca') == $m->slug ? 'bg-gray-100 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    {{ $m->nome }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
