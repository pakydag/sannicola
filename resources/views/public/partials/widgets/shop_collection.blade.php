<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($widget->titolo)
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ $widget->titolo }}
                </h2>
                <div class="mt-2 h-1 w-20 bg-indigo-600 mx-auto rounded"></div>
            </div>
        @endif

        @php
            $cid = $widget->data['shop_collection_id'] ?? null;
            $limit = $widget->data['limit'] ?? 4;
            $collezione = $cid ? \App\Models\ShopCollection::with('tags')->find($cid) : null;
            
            $prodotti = collect();
            if ($collezione) {
                $tagIds = $collezione->tags->pluck('id');
                $prodotti = \App\Models\ShopProduct::where('visibile', true)
                    ->where(function($q) use ($collezione, $tagIds) {
                        $q->where('shop_collection_id', $collezione->id);
                        if ($tagIds->count() > 0) {
                            $q->orWhereHas('tags', function($q2) use ($tagIds) {
                                $q2->whereIn('tags.id', $tagIds);
                            });
                        }
                    })
                    ->with(['variants', 'collection'])
                    ->orderBy('ordine')
                    ->take($limit)
                    ->get();
            }
            $settings = \App\Models\Setting::pluck('value', 'key')->all();
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
                        
                        @if($prodotto->variants->first() && $prodotto->variants->first()->prezzo_scontato > 0)
                            <div class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full shadow-lg">Saldi</div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col">
                        @if($prodotto->brand)
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->brand->nome }}</span>
                        @elseif($prodotto->marca)
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ $prodotto->marca }}</span>
                        @endif
                        
                        <h3 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-4 line-clamp-2 min-h-[40px]">{{ $prodotto->nome }}</h3>
                        
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
                                            <span class="text-base font-black text-gray-900 leading-none">€ {{ number_format($prezzo, 2, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">Su richiesta</span>
                                    @endif
                                @else
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Dettagli</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('public.shop.prodotto', ['collezione_slug' => $prodotto->collection?->slug ?? ($collezione?->slug ?? 'all'), 'prodotto_slug' => $prodotto->slug]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-900 text-white rounded-lg hover:bg-indigo-600 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-400 italic">
                    Nessun prodotto disponibile per questa collezione.
                </div>
            @endforelse
        </div>
        
        @if($collezione)
            <div class="mt-12 text-center">
                <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="inline-flex items-center px-6 py-3 border border-indigo-600 text-indigo-600 font-bold rounded-full hover:bg-indigo-600 hover:text-white transition-all">
                    Vedi tutta la collezione
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        @endif
    </div>
</div>
