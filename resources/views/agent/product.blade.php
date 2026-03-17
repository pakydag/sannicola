<x-agent-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('agent.catalog') }}" class="bg-white border border-gray-200 text-gray-500 p-2 rounded-xl hover:bg-gray-50 transition">
                <span class="text-xl leading-none">←</span>
            </a>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
                {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Image & Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-12 aspect-square flex items-center justify-center relative overflow-hidden group">
                <span class="absolute top-8 left-8 bg-indigo-900 text-white px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-xl">
                    {{ $product->brand->name }}
                </span>
                
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition duration-700">
                @else
                    <div class="text-9xl opacity-10">👕</div>
                @endif
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="font-black text-sm uppercase tracking-widest text-gray-500 mb-4 border-b pb-2">Dettagli Articolo</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 font-bold uppercase text-xs mb-1">Marchio</p>
                        <p class="font-black text-indigo-900">{{ $product->brand->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-bold uppercase text-xs mb-1">Stagione</p>
                        <p class="font-black text-indigo-900">{{ $product->season ?? 'N/D' }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-gray-500 font-bold uppercase text-xs mb-1">Descrizione</p>
                    <p class="text-sm text-gray-700 font-medium leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $product->description ?? 'Nessuna descrizione disponibile per questo articolo.' }}</p>
                </div>
            </div>
        </div>

        <!-- Variant Matrix & Form -->
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-8 md:p-12 overflow-hidden">
            <h3 class="font-black text-xl text-gray-900 uppercase tracking-tight mb-8">Selezione Taglie & Colori</h3>
            
            <form action="{{ route('agent.cart.add') }}" method="POST">
                @csrf
                
                @php
                    $groupedVariants = $product->variants->groupBy(function($v) { return $v->color ?? 'UNICO'; });
                    $sizes = $product->variants->pluck('size')->unique()->sort();
                @endphp

                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="py-4 px-2 whitespace-nowrap">Variante / Colore</th>
                                @foreach($sizes as $size)
                                    <th class="py-4 px-2 text-center whitespace-nowrap">{{ $size }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($groupedVariants as $color => $variants)
                                <tr class="group hover:bg-indigo-50/30 transition">
                                    <td class="py-6 px-2">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full mr-3 bg-indigo-400"></div>
                                            <span class="text-xs font-black text-gray-900 uppercase">{{ $color }}</span>
                                        </div>
                                    </td>
                                    @foreach($sizes as $size)
                                        <td class="py-6 px-1 text-center">
                                            @php 
                                                $variant = $variants->where('size', $size)->first(); 
                                                $index = $variant ? $variant->id : 'none';
                                            @endphp
                                            @if($variant)
                                                <div class="relative inline-block">
                                                    <input type="hidden" name="variants[{{ $variant->id }}][id]" value="{{ $variant->id }}">
                                                    <input type="number" 
                                                           name="variants[{{ $variant->id }}][quantity]" 
                                                           min="0" 
                                                           max="{{ $variant->quantity }}" 
                                                           value="0" 
                                                           placeholder="0"
                                                           class="qty-input w-16 text-center text-xs font-black border-gray-100 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition p-2 bg-gray-50 hover:bg-white">
                                                    
                                                    @if($variant->quantity <= 5 && $variant->quantity > 0)
                                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-[8px] font-black text-orange-500 whitespace-nowrap bg-white px-1 shadow-sm rounded border border-orange-50 mb-1">
                                                            SOLO {{ $variant->quantity }}
                                                        </span>
                                                    @elseif($variant->quantity <= 0)
                                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-[8px] font-black text-rose-400 whitespace-nowrap bg-white px-1 shadow-sm rounded border border-rose-50 mb-1">
                                                            ESAURITO
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="w-16 mx-auto h-8 bg-gray-50/50 rounded-lg flex items-center justify-center opacity-30">
                                                    <div class="w-1 h-3 bg-gray-200 rotate-45 transform"></div>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-12 bg-gray-50 rounded-[32px] p-8 border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Riepilogo Selezione</span>
                        <div class="flex items-baseline gap-2">
                            <span id="total-qty-display" class="text-4xl font-black text-indigo-900 leading-none">0</span>
                            <span class="text-xs font-black text-indigo-400 uppercase tracking-widest">Pezzi Totali</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto px-12 bg-indigo-600 text-white py-5 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition duration-300 flex items-center justify-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Aggiungi al Carrello
                    </button>
                </div>
                <p class="text-[10px] text-indigo-400 text-center mt-4 font-black uppercase tracking-widest opacity-60">L'ordine verrà salvato come bozza nel carrello B2B.</p>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.qty-input');
            const display = document.getElementById('total-qty-display');
            
            const updateTotal = () => {
                let total = 0;
                inputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                display.innerText = total;
            };

            inputs.forEach(input => {
                input.addEventListener('input', updateTotal);
                input.addEventListener('change', updateTotal);
            });
        });
    </script>
    @endpush
</x-agent-layout>
