<x-agent-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">
            {{ __('I Tuoi Ordini Inviati') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50">
                <tr class="text-xs font-black text-gray-500 uppercase tracking-widest border-b border-gray-100">
                    <th class="px-8 py-5">ID Ordine</th>
                    <th class="px-8 py-5">Cliente B2B</th>
                    <th class="px-8 py-5 text-center">Data</th>
                    <th class="px-8 py-5 text-center">Stato</th>
                    <th class="px-8 py-5 text-right">Totale</th>
                    <th class="px-8 py-5 text-right">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="group hover:bg-indigo-50/20 transition">
                        <td class="px-8 py-6 text-sm font-black text-gray-900 uppercase leading-none">
                            #{{ $order->id }}
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-indigo-900 uppercase text-sm">{{ $order->customer->business_name }}</p>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ $order->customer->vat_number }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs font-bold text-gray-500">{{ $order->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs px-3 py-1 rounded-full font-black uppercase tracking-widest
                                {{ $order->status == 'pending' ? 'bg-amber-100 text-amber-700' : 
                                   ($order->status == 'confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="font-black text-gray-900">€ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('agent.order_detail', $order) }}" class="inline-block bg-indigo-900 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition duration-300">
                                Dettaglio B2B
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-gray-500 uppercase tracking-widest text-xs font-bold">
                            Non hai ancora inviato alcun ordine al sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-agent-layout>
