<div class="py-12 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($widget->titolo)
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl uppercase">
                    {{ $widget->titolo }}
                </h2>
                <div class="mt-2 h-0.5 w-16 bg-indigo-500 mx-auto rounded-full"></div>
            </div>
        @endif

        @php
            $marche = \App\Models\ShopBrand::where('visibile', true)->orderBy('ordine')->get();
            $style = $widget->data['style'] ?? 'grid';
        @endphp

        @if($style === 'carousel')
            <div class="relative">
                <div class="flex overflow-x-auto pb-8 snap-x no-scrollbar space-x-8">
                    @forelse($marche as $marca)
                        <div class="flex-shrink-0 w-32 md:w-40 snap-center group">
                            <a href="{{ route('public.shop.index', ['marca' => $marca->slug]) }}" class="block">
                                <div class="bg-gray-50 rounded-2xl p-6 aspect-square flex items-center justify-center grayscale transition-all duration-300 group-hover:grayscale-0 group-hover:bg-white group-hover:shadow-lg border border-transparent group-hover:border-gray-100">
                                    @if($marca->foto)
                                        <img src="{{ asset($marca->foto) }}" alt="{{ $marca->nome }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="text-sm font-bold text-gray-400 text-center">{{ $marca->nome }}</span>
                                    @endif
                                </div>
                                <p class="mt-3 text-center text-xs font-bold text-gray-500 group-hover:text-indigo-600 transition-colors uppercase tracking-widest">{{ $marca->nome }}</p>
                            </a>
                        </div>
                    @empty
                        <p class="w-full text-center text-gray-400 italic">Nessun marchio disponibile.</p>
                    @endforelse
                </div>
            </div>
            
            <style>
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @forelse($marche as $marca)
                    <a href="{{ route('public.shop.index', ['marca' => $marca->slug]) }}" class="group">
                        <div class="bg-gray-50 rounded-2xl p-6 aspect-square flex items-center justify-center grayscale transition-all duration-300 group-hover:grayscale-0 group-hover:bg-white group-hover:shadow-xl border border-transparent group-hover:border-gray-100">
                            @if($marca->foto)
                                <img src="{{ asset($marca->foto) }}" alt="{{ $marca->nome }}" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-sm font-bold text-gray-400 text-center">{{ $marca->nome }}</span>
                            @endif
                        </div>
                        <p class="mt-3 text-center text-[10px] font-bold text-gray-400 group-hover:text-indigo-600 transition-colors uppercase tracking-widest">{{ $marca->nome }}</p>
                    </a>
                @empty
                    <div class="col-span-full py-8 text-center text-gray-400 italic">
                        Nessun marchio disponibile.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
