@php
    $macros = \App\Models\ShopCategory::whereNull('parent_id')
        ->with(['children' => function($q) {
            $q->where('visibile', true)->orderBy('ordine');
        }, 'children.children' => function($q) {
            $q->where('visibile', true)->orderBy('ordine');
        }])
        ->where('visibile', true)
        ->orderBy('ordine')
        ->get();

    $marche = \App\Models\ShopBrand::where('visibile', true)->orderBy('ordine')->get();

    $displayCategories = $macros;
    $currentLevel = 'macro';

    // Logica di salto livelli singoli (Singleton skipping)
    if ($macros->count() === 1) {
        $singleMacro = $macros->first();
        if ($singleMacro->children->count() > 0) {
            $displayCategories = $singleMacro->children;
            $currentLevel = 'categoria';
            
            if ($displayCategories->count() === 1) {
                $singleCat = $displayCategories->first();
                if ($singleCat->children->count() > 0) {
                    $displayCategories = $singleCat->children;
                    $currentLevel = 'sottocategoria';
                }
            }
        }
    }
@endphp

<div class="hidden lg:block space-y-6">
<div x-data="{ open: true }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <button type="button" @click="open = !open" class="w-full bg-gray-50 px-6 py-4 flex items-center justify-between text-left focus:outline-none transition-colors hover:bg-gray-100 cursor-pointer" :class="{ 'border-b border-gray-100': open }">
        <div class="flex items-center space-x-2">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Categorie</h3>
        </div>
        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" x-cloak x-collapse class="p-2">
        <nav class="space-y-1">
            @foreach($displayCategories as $item)
                @php
                    $isOpen = isset($categoria) && (
                        $categoria->id == $item->id || 
                        $categoria->parent_id == $item->id || 
                        ($categoria->parent && $categoria->parent->parent_id == $item->id)
                    );
                @endphp
                <div x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" class="group">
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-gray-100 transition-all cursor-pointer @if($isOpen) bg-gray-100 @endif">
                        <a href="{{ route('public.shop.categoria', $item->slug) }}" class="flex-grow text-sm font-semibold @if(isset($categoria) && $categoria->id == $item->id) text-primary @else text-gray-700 @endif group-hover:text-primary transition-colors">
                            {{ $item->nome }}
                        </a>
                        @if($item->children->count() > 0)
                            <button @click="open = !open" class="p-1 text-gray-400 hover:text-secondary focus:outline-none">
                                <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    @if($item->children->count() > 0)
                        <div x-show="open" x-cloak x-collapse class="pl-4 pr-2 pb-2 space-y-1">
                            @foreach($item->children as $child)
                                @php
                                    $isSubOpen = isset($categoria) && (
                                        $categoria->id == $child->id || 
                                        $categoria->parent_id == $child->id
                                    );
                                @endphp
                                <div x-data="{ subOpen: {{ $isSubOpen ? 'true' : 'false' }} }">
                                    <div class="flex items-center justify-between px-4 py-2 rounded-lg hover:bg-white hover:transition-all @if(isset($categoria) && $categoria->id == $child->id) bg-white @endif">
                                        <a href="{{ route('public.shop.categoria', $child->slug) }}" class="flex-grow text-xs @if(isset($categoria) && $categoria->id == $child->id) text-primary font-bold @else text-gray-500 @endif hover:text-primary">
                                            {{ $child->nome }}
                                        </a>
                                        @if($child->children->count() > 0)
                                            <button @click="subOpen = !subOpen" class="p-0.5 text-gray-300 hover:text-secondary focus:outline-none">
                                                <svg class="w-3 h-3 transform transition-transform" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($child->children->count() > 0)
                                        <div x-show="subOpen" x-cloak x-collapse class="pl-4 py-1 space-y-0.5">
                                            @foreach($child->children as $sub)
                                                <a href="{{ route('public.shop.categoria', $sub->slug) }}" class="block px-4 py-1.5 text-xs @if(isset($categoria) && $categoria->id == $sub->id) text-secondary font-bold @else text-gray-400 @endif hover:text-primary ml-1">
                                                    {{ $sub->nome }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </div>
</div>

<div x-data="{ open: {{ request('marca') ? 'true' : 'false' }} }" class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <button type="button" @click="open = !open" class="w-full bg-gray-50 px-6 py-4 flex items-center justify-between text-left focus:outline-none transition-colors hover:bg-gray-100 cursor-pointer" :class="{ 'border-b border-gray-100': open }">
        <div class="flex items-center space-x-2">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Marche</h3>
            @if(request('marca'))
                @php
                    $currentMarca = $marche->firstWhere('slug', request('marca'));
                @endphp
                @if($currentMarca)
                    <span class="text-xs font-semibold text-primary">({{ $currentMarca->nome }})</span>
                @endif
            @endif
        </div>
        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" x-cloak x-collapse class="p-2">
        <nav class="space-y-1">
            @if(request('marca'))
                <a href="{{ route('public.shop.index') }}" class="block px-4 py-2 text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all mb-1">
                    ✕ Rimuovi filtro marca
                </a>
            @endif
            @foreach($marche as $m)
                @php
                    $isActive = request('marca') == $m->slug;
                @endphp
                <a href="{{ route('public.shop.index', ['marca' => $m->slug]) }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold transition-all @if($isActive) bg-gray-200 text-primary @else text-gray-700 hover:bg-gray-100 hover:text-primary @endif">
                    {{ $m->nome }}
                </a>
            @endforeach
        </nav>
    </div>
</div>

<a href="{{ route('public.shop.index', ['in_offerta' => 1]) }}" class="mt-6 block bg-secondary hover:brightness-105 rounded-2xl p-5 text-white shadow-lg overflow-hidden relative group transition-all duration-300 transform hover:-translate-y-0.5 @if(request('in_offerta')) ring-0 @endif">
    <div class="relative z-10 flex items-center justify-between">
        <span class="font-bold text-white text-base uppercase tracking-wider">In Offerta</span>
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </div>
    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
</a>
</div>

