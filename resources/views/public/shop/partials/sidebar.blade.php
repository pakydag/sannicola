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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Categorie</h3>
    </div>
    <div class="p-2">
        <nav class="space-y-1">
            @foreach($displayCategories as $item)
                <div x-data="{ open: false }" class="group">
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-indigo-50 transition-all cursor-pointer @if(isset($categoria) && ($categoria->id == $item->id || $categoria->parent_id == $item->id)) bg-indigo-50/50 @endif">
                        <a href="{{ route('public.shop.categoria', $item->slug) }}" class="flex-grow text-sm font-semibold @if(isset($categoria) && $categoria->id == $item->id) text-indigo-600 @else text-gray-700 @endif group-hover:text-indigo-600 transition-colors">
                            {{ $item->nome }}
                        </a>
                        @if($item->children->count() > 0)
                            <button @click="open = !open" class="p-1 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    @if($item->children->count() > 0)
                        <div x-show="open" x-cloak x-collapse class="pl-4 pr-2 pb-2 space-y-1">
                            @foreach($item->children as $child)
                                <div x-data="{ subOpen: false }">
                                    <div class="flex items-center justify-between px-4 py-2 rounded-lg hover:bg-white hover:shadow-sm transition-all @if(isset($categoria) && $categoria->id == $child->id) bg-white shadow-sm ring-1 ring-indigo-100 @endif">
                                        <a href="{{ route('public.shop.categoria', $child->slug) }}" class="flex-grow text-xs @if(isset($categoria) && $categoria->id == $child->id) text-indigo-600 font-bold @else text-gray-500 @endif hover:text-indigo-600">
                                            {{ $child->nome }}
                                        </a>
                                        @if($child->children->count() > 0)
                                            <button @click="subOpen = !subOpen" class="p-0.5 text-gray-300 hover:text-indigo-400 focus:outline-none">
                                                <svg class="w-3 h-3 transform transition-transform" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($child->children->count() > 0)
                                        <div x-show="subOpen" x-cloak x-collapse class="pl-4 py-1 space-y-0.5">
                                            @foreach($child->children as $sub)
                                                <a href="{{ route('public.shop.categoria', $sub->slug) }}" class="block px-4 py-1.5 text-[11px] @if(isset($categoria) && $categoria->id == $sub->id) text-indigo-600 font-bold @else text-gray-400 @endif hover:text-indigo-500 border-l border-gray-100 ml-1">
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

<div class="mt-6 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-lg overflow-hidden relative group">
    <div class="relative z-10">
        <h4 class="font-extrabold text-lg mb-2">Offerta Speciale</h4>
        <p class="text-indigo-100 text-xs leading-relaxed mb-4">Scopri i nostri prodotti in promozione con sconti fino al 30%!</p>
        <a href="#" class="bottone-personalizzato mt-4 inline-flex items-center text-xs font-bold bg-white text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-50 transition-all shadow-sm">
            Vedi Offerte
            <svg class="ml-2 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
        </a>
    </div>
    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
</div>
