<x-agent-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
            {{ __('Il Tuo Carrello B2B') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        @if(empty($cart))
            <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-20 text-center">
                <span class="text-8xl block mb-6">🛒</span>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Il carrello è vuoto</h3>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm mb-10">Sfoglia il catalogo per iniziare una nuova raccolta ordini.</p>
                <a href="{{ route('agent.catalog') }}" class="inline-block bg-indigo-600 text-white px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition">Torna al Catalogo</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Items list -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-xs font-black text-gray-500 uppercase tracking-widest border-b border-gray-100">
                                    <th class="px-8 py-4">Articolo</th>
                                    <th class="px-8 py-4 text-center">Dettagli</th>
                                    <th class="px-8 py-4 text-center">Prezzo</th>
                                    <th class="px-8 py-4 text-center">Q.tà</th>
                                    <th class="px-8 py-4 text-right">Subtotale</th>
                                    <th class="px-8 py-4 text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($cart as $index => $item)
                                    <tr class="group hover:bg-gray-50/50 transition">
                                        <td class="px-8 py-6">
                                            <p class="font-black text-gray-900 uppercase leading-tight">{{ $item['name'] }}</p>
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ $item['brand'] }}</p>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg font-bold text-xs uppercase tracking-tight">
                                                {{ $item['color'] ?? 'Unico' }} / {{ $item['size'] }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-center font-bold text-gray-900 uppercase">
                                            € {{ number_format($item['price'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-6 text-center font-black text-gray-900">{{ $item['quantity'] }}</td>
                                        <td class="px-8 py-6 text-right font-black text-indigo-900">
                                            € {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <form action="{{ route('agent.cart.remove', $index) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:text-rose-600 transition">
                                                    <span class="text-xl leading-none">×</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Checkout Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-10 space-y-8 sticky top-6">
                        <div>
                            <h3 class="font-black text-lg text-gray-900 uppercase tracking-tight mb-4 border-b border-gray-50 pb-2">Selezione Cliente</h3>
                            <p class="text-xs text-gray-400 italic mb-6">Per procedere all'invio dell'ordine, seleziona il cliente nell'anagrafica autorizzata.</p>
                            
                            <form action="{{ route('agent.process_checkout') }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="space-y-6">
                                    <div x-data="{ 
                                            search: '',
                                            show: false,
                                            selectedName: 'Seleziona un cliente...',
                                            selectedId: '',
                                            customers: [
                                                @foreach($customers as $customer)
                                                { id: '{{ $customer->id }}', name: '{{ addslashes($customer->business_name) }}', vat: '{{ $customer->vat_number }}' },
                                                @endforeach
                                            ],
                                            get filteredCustomers() {
                                                if (this.search === '') return this.customers;
                                                return this.customers.filter(c => 
                                                    c.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                                    c.vat.toLowerCase().includes(this.search.toLowerCase())
                                                );
                                            }
                                        }" class="relative">
                                        
                                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Cliente Autorizzato *</label>
                                        
                                        <!-- Custom Searchable Select -->
                                        <div class="relative">
                                            <button type="button" @click="show = !show" 
                                                    class="w-full bg-white border border-gray-200 rounded-2xl p-4 text-left text-base font-bold flex justify-between items-center focus:ring-2 focus:ring-indigo-500 transition">
                                                <span x-text="selectedName" :class="selectedId ? 'text-gray-900' : 'text-gray-400'"></span>
                                                <span class="text-xs">▼</span>
                                            </button>
                                            
                                            <input type="hidden" name="b2b_customer_id" x-model="selectedId" required>

                                            <div x-show="show" @click.away="show = false" 
                                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden" 
                                                 x-cloak>
                                                <div class="p-3 border-b border-gray-50">
                                                    <input type="text" x-model="search" placeholder="Cerca per nome o P.IVA..." 
                                                           class="w-full border-gray-100 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 bg-gray-50">
                                                </div>
                                                <div class="max-h-60 overflow-y-auto">
                                                    <template x-for="customer in filteredCustomers" :key="customer.id">
                                                        <div @click="selectedId = customer.id; selectedName = customer.name; show = false; search = ''" 
                                                             class="px-4 py-3 hover:bg-indigo-50 cursor-pointer transition">
                                                            <p class="font-black text-gray-900 text-sm uppercase" x-text="customer.name"></p>
                                                            <p class="text-[10px] text-gray-400 font-bold" x-text="'P.IVA: ' + customer.vat"></p>
                                                        </div>
                                                    </template>
                                                    <div x-show="filteredCustomers.length === 0" class="p-4 text-center text-xs text-gray-400 italic">
                                                        Nessun cliente trovato per questa ricerca.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Note per la sede</label>
                                        <textarea name="notes" placeholder="Eventuali istruzioni speciali..." rows="4" class="w-full border-gray-200 rounded-2xl text-base focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>

                                    <div class="bg-indigo-900 rounded-3xl p-8 text-white shadow-xl">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold uppercase opacity-60">Pezzi Totali</span>
                                            <span class="text-xl font-black">{{ collect($cart)->sum('quantity') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-8">
                                            <span class="text-xs font-bold uppercase opacity-60">Totale Ordine</span>
                                            <span class="text-3xl font-black">€ {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2, ',', '.') }}</span>
                                        </div>
                                        
                                        <button type="submit" class="w-full bg-white text-indigo-900 py-5 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-indigo-50 shadow-xl transition duration-300">
                                            Invia l'Ordine alla Sede
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-agent-layout>
