<x-agent-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
            {{ __('Benvenuto,') }} {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition duration-500">📝</div>
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Totale Ordini</p>
                <p class="text-4xl font-black text-indigo-900">{{ $stats['orders_count'] }}</p>
                <p class="text-xs text-indigo-400 font-bold mt-2 uppercase tracking-tight">Inviati alla sede</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition duration-500">⏳</div>
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Pendenti</p>
                <p class="text-4xl font-black text-amber-500">{{ $stats['pending_orders'] }}</p>
                <p class="text-xs text-amber-400 font-bold mt-2 uppercase tracking-tight">In attesa di conferma</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition duration-500">💰</div>
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest mb-1">Volume Confermato</p>
                <p class="text-4xl font-black text-emerald-600">€ {{ number_format($stats['total_volume'], 2, ',', '.') }}</p>
                <p class="text-xs text-emerald-400 font-bold mt-2 uppercase tracking-tight">Valore ordini approvati</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-black text-gray-800 uppercase text-sm tracking-wider">Ultimi Ordini Inviati</h3>
                    <a href="{{ route('agent.orders') }}" class="text-xs font-bold text-indigo-600 hover:underline">Vedi Archivio</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recent_orders as $order)
                        <div class="px-8 py-5 flex justify-between items-center hover:bg-indigo-50/30 transition">
                            <div>
                                <p class="text-base font-black text-gray-900">#{{ $order->id }} - {{ $order->customer->business_name }}</p>
                                <p class="text-xs text-gray-500 font-bold uppercase">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-base font-black text-indigo-900">€ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase 
                                    {{ $order->status == 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-8 py-12 text-center text-gray-500 uppercase tracking-widest text-xs font-bold font-medium">
                            Non hai ancora inviato alcun ordine.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 gap-6">
                <a href="{{ route('agent.catalog') }}" class="bg-indigo-600 p-8 rounded-3xl text-white shadow-lg shadow-indigo-200 hover:scale-[1.02] transition duration-300 flex items-center justify-between group">
                    <div>
                        <h4 class="text-2xl font-black mb-1">Nuovo Ordine</h4>
                        <p class="text-indigo-100 text-sm font-medium opacity-90">Sfoglia il catalogo e inserisci una nuova raccolta.</p>
                    </div>
                    <span class="text-4xl group-hover:translate-x-2 transition">➔</span>
                </a>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-black text-gray-800 uppercase text-sm tracking-wider mb-4 border-b pb-2">Assistenza B2B</h4>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        In caso di problemi tecnici con il portale o discrepanze nell'inventario, contatta l'amministrazione via e-mail o utilizza la chat di supporto.
                    </p>
                    <div class="flex space-x-4">
                        <button class="bg-gray-100 text-gray-800 text-xs font-black uppercase px-4 py-2 rounded-xl hover:bg-gray-200 transition">Email Supporto</button>
                        <button class="bg-gray-100 text-gray-800 text-xs font-black uppercase px-4 py-2 rounded-xl hover:bg-gray-200 transition">Manuale PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-agent-layout>
