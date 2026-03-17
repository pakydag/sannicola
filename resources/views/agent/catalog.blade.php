<x-agent-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
                {{ __('Catalogo Prodotti authorized') }}
            </h2>
            
            <!-- Filtri -->
            <form action="{{ route('agent.catalog') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto">
                <select name="brand" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-xl px-4 py-2 focus:ring-indigo-500">
                    <option value="">Tutti i Brand</option>
                    @foreach($authorizedBrands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca prodotto..." class="text-sm border-gray-200 rounded-xl px-4 py-2 focus:ring-indigo-500 w-full md:w-64">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition">Cerca</button>
            </form>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl hover:shadow-indigo-50 transition duration-500 group">
                <div class="aspect-square bg-gray-50 flex items-center justify-center p-8 relative">
                    <!-- Badge Marchio -->
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm border border-gray-100 px-3 py-1 rounded-full text-xs font-black uppercase text-gray-600 tracking-wider shadow-sm">
                        {{ $product->brand->name }}
                    </span>
                    
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition duration-500">
                    @else
                        <div class="text-6xl opacity-20">👕</div>
                    @endif
                </div>
                
                <div class="p-6 flex-1 flex flex-col">
                    <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">{{ $product->season ?? 'Qualsiasi Stagione' }}</p>
                    <h3 class="text-lg font-black text-gray-900 leading-tight mb-2 uppercase">{{ $product->name }}</h3>
                    
                    <div class="flex-1 text-sm text-gray-500 italic mb-4 line-clamp-2">
                        {{ $product->description ?? 'Nessuna descrizione disponibile.' }}
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                        <div class="flex flex-wrap gap-1">
                            @foreach($product->variants->pluck('size')->unique() as $size)
                                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold">{{ $size }}</span>
                            @endforeach
                            @if($product->variants->isEmpty())
                                <span class="text-xs text-rose-400 font-bold uppercase italic">Esaurito</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-6 mt-auto">
                    <div class="flex items-end justify-between">
                        <p class="text-xl font-black text-indigo-900 leading-none">
                            € {{ number_format($product->price, 2, ',', '.') }}
                        </p>
                        <a href="{{ route('agent.product', $product) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition duration-300">
                            Vedi Dettaglio
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <span class="text-6xl block mb-4">🔍</span>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Nessun prodotto trovato per i filtri selezionati.</p>
            </div>
        @endforelse
    </div>
</x-agent-layout>
