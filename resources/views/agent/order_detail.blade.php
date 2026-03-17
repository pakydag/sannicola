<x-agent-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('agent.orders') }}" class="bg-white border border-gray-200 text-gray-500 p-2 rounded-xl hover:bg-gray-50 transition">
                <span class="text-xl leading-none">←</span>
            </a>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
                Dettaglio Ordine #{{ $order->id }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Order Header Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Informazioni Cliente</p>
                <p class="text-lg font-black text-indigo-900 uppercase tracking-tight">{{ $order->customer->business_name }}</p>
                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    <p><strong class="uppercase text-[10px] text-gray-400">P.IVA:</strong> {{ $order->customer->vat_number }}</p>
                    <p><strong class="uppercase text-[10px] text-gray-400">REF:</strong> {{ $order->customer->contact_person }}</p>
                    <p><strong class="uppercase text-[10px] text-gray-400">TEL:</strong> {{ $order->customer->phone }}</p>
                    <p><strong class="uppercase text-[10px] text-gray-400">MAIL:</strong> {{ $order->customer->email }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Riepilogo Ordine</p>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-xs font-bold text-gray-400 uppercase">Stato Attuale</span>
                        <span class="text-xs font-black uppercase text-amber-500">{{ $order->status }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-xs font-bold text-gray-400 uppercase">Data Invio</span>
                        <span class="text-xs font-black uppercase text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-xs font-bold text-gray-400 uppercase">Totale Pezzi</span>
                        <span class="text-xs font-black uppercase text-gray-900">{{ $order->items->sum('quantity') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Note dalla Sede/Agente</p>
                <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                    {{ $order->notes ?? 'Nessuna nota aggiuntiva inserita per questo ordine.' }}
                </p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-wider">Articoli in Ordine</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50/20">
                    <tr class="text-xs font-black text-gray-500 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-8 py-5">Prodotto</th>
                        <th class="px-8 py-5 text-center">Colore</th>
                        <th class="px-8 py-5 text-center">Taglia</th>
                        <th class="px-8 py-5 text-center">Quantità</th>
                        <th class="px-8 py-5 text-right">Prezzo Unit.</th>
                        <th class="px-8 py-5 text-right">Subtotale</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-indigo-50/20 transition">
                            <td class="px-8 py-6">
                                <p class="font-black text-gray-900 uppercase text-sm leading-tight">{{ $item->product->name }}</p>
                                <p class="text-xs text-indigo-400 font-bold uppercase tracking-tight">{{ $item->product->brand->name }}</p>
                            </td>
                            <td class="px-8 py-6 text-center text-sm font-bold uppercase text-gray-600">{{ $item->variant->color ?? 'Unico' }}</td>
                            <td class="px-8 py-6 text-center text-sm font-black text-indigo-900">{{ $item->variant->size }}</td>
                            <td class="px-8 py-6 text-center font-black text-gray-900 text-base">{{ $item->quantity }}</td>
                            <td class="px-8 py-6 text-right text-sm font-bold text-gray-600">€ {{ number_format($item->price, 2, ',', '.') }}</td>
                            <td class="px-8 py-6 text-right font-black text-indigo-900 text-base">€ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/50">
                    <tr>
                        <td colspan="5" class="px-8 py-8 text-right text-xs font-black text-gray-500 uppercase tracking-widest">Valore Totale Ordine</td>
                        <td class="px-8 py-8 text-right text-3xl font-black text-indigo-900">€ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-agent-layout>
