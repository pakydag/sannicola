<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($widget->titolo)
            <div class="mb-10 text-center">
                <h2 class="text-3xl sm:text-4xl">
                    {{ $widget->titolo }}
                </h2>
                <div class="separatore"></div>
            </div>
        @endif

        @php
            $mode = $widget->data['mode'] ?? 'manual';
            $prodotti = collect();
            $settings = \App\Models\Setting::pluck('value', 'key')->all();

            if ($mode === 'manual' && isset($widget->data['product_ids'])) {
                $prodotti = \App\Models\ShopProduct::whereIn('id', $widget->data['product_ids'])
                            ->where('visibile', true)
                            ->orderByRaw("FIELD(id, " . implode(',', $widget->data['product_ids']) . ")")
                            ->get();
            } elseif ($mode === 'category' && isset($widget->data['shop_category_id'])) {
                $catId = (int)$widget->data['shop_category_id'];
                $categoria = \App\Models\ShopCategory::with('children', 'children.children')->find($catId);
                
                if ($categoria) {
                    $categoryIds = [$categoria->id];
                    
                    // Add children
                    foreach ($categoria->children as $child) {
                        $categoryIds[] = $child->id;
                        // Add grandchildren
                        foreach ($child->children as $subChild) {
                            $categoryIds[] = $subChild->id;
                        }
                    }
                    
                    $categoryIds = array_unique($categoryIds);
                    $limit = $widget->data['cat_limit'] ?? 4;
                    
                    $prodotti = \App\Models\ShopProduct::whereIn('shop_category_id', $categoryIds)
                                ->where('visibile', true)
                                ->with(['variants', 'collection'])
                                ->orderBy('created_at', 'desc')
                                ->take($limit)
                                ->get();
                }
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($prodotti as $prodotto)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition-all hover:shadow-xl hover:-translate-y-1 group">
                    @php
                        $primaFoto = is_array($prodotto->foto_aggiuntive) && count($prodotto->foto_aggiuntive) > 0 ? $prodotto->foto_aggiuntive[0] : null;
                    @endphp
                    
                    <div class="relative overflow-hidden aspect-[4/3]">
                        @if($primaFoto)
                            <img src="{{ $primaFoto }}" alt="{{ $prodotto->nome }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col">
                        @if($prodotto->brand)
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->brand->nome }}</span>
                        @elseif($prodotto->marca)
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->marca }}</span>
                        @endif
                        
                        <h3 class="text-sm font-semibold transition-colors mb-4 line-clamp-2 min-h-[40px]">{{ $prodotto->nome }}</h3>
                        
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                            @php
                                $variant = $prodotto->variants->first();
                                $prezzo = $variant ? $variant->prezzo : 0;
                                $prezzo_scontato = $variant ? $variant->prezzo_scontato : null;
                                
                                $showPreview = ($settings['shop_price_preview'] ?? '0') == '1';
                                if ($showPreview) {
                                    $priceData = $prodotto->getLowestPrice();
                                    $prezzo = $priceData['prezzo'];
                                    $prezzo_scontato = $priceData['prezzo_scontato'];
                                }
                            @endphp
                            
                            <div class="flex flex-col">
                                @if($showPreview)
                                    @if($prezzo_scontato > 0)
                                        <div class="flex flex-col">
                                            <span class="text-base font-black text-red-600 leading-none">€ {{ number_format($prezzo_scontato, 2, ',', '.') }}</span>
                                            <span class="text-[10px] line-through text-gray-400 mt-1">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                        </div>
                                    @elseif($prezzo > 0)
                                        <div class="flex flex-col">
                                            <span class="text-base font-normal text-gray-900 leading-none">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">Su richiesta</span>
                                    @endif
                                @else
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Dettagli</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $prodotto->collection?->slug ?? 'all', 'prodotto_slug' => $prodotto->slug]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded-lg hover:bg-secondary transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-400 italic">
                    Nessun prodotto selezionato.
                </div>
            @endforelse
        </div>
    </div>
</div>
