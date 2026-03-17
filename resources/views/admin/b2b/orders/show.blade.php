<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dettaglio Ordine B2B #') }}{{ $order->id }}
            </h2>
            <div class="flex space-x-2">
                @if($order->status == 'pending')
                    <form action="{{ route('admin.b2b.orders.update', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-bold uppercase transition">Conferma Ordine</button>
                    </form>
                    <form action="{{ route('admin.b2b.orders.update', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-bold uppercase transition">Annulla</button>
                    </form>
                @endif
                <a href="{{ route('admin.b2b.orders.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition">Indietro</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Cliente -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-bold text-gray-700 uppercase text-xs mb-4 border-b pb-2">Dati Cliente</h3>
                    <p class="text-lg font-bold">{{ $order->customer->business_name }}</p>
                    <p class="text-sm text-gray-600">P.IVA: {{ $order->customer->vat_number }}</p>
                    <p class="text-sm text-gray-600">Ref: {{ $order->customer->contact_name }} {{ $order->customer->contact_surname }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer->email }}</p>
                </div>
                <!-- Info Agente -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-bold text-gray-700 uppercase text-xs mb-4 border-b pb-2">Agente Incaricato</h3>
                    <p class="text-lg font-bold">{{ $order->agent->name }} {{ $order->agent->surname }}</p>
                    <p class="text-sm text-gray-600">Email: {{ $order->agent->email }}</p>
                </div>
                <!-- Riepilogo -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-bold text-gray-700 uppercase text-xs mb-4 border-b pb-2">Stato & Pagamento</h3>
                    <div class="flex justify-between items-center mb-2">
                        <span>Stato:</span>
                        @if($order->status == 'pending')
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs uppercase font-bold">In attesa</span>
                        @elseif($order->status == 'confirmed')
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs uppercase font-bold">Confermato</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs uppercase font-bold">Annullato</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Pagamento:</span>
                        <span class="font-bold">{{ $order->payment_method ?? 'Trattativa privata' }}</span>
                    </div>
                </div>
            </div>

            <!-- Dettaglio Righe -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Prodotto</th>
                            <th class="px-6 py-3 text-center">Variante (Colore/Taglia)</th>
                            <th class="px-6 py-3 text-center">Quantità</th>
                            <th class="px-6 py-3 text-right">Prezzo Unit.</th>
                            <th class="px-6 py-3 text-right">Totale Riga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->product->brand->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    {{ $item->variant->color }} / {{ $item->variant->size }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm">€ {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold">€ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right font-bold uppercase text-xs">Totale Finale Ordine</td>
                            <td class="px-6 py-4 text-right text-xl font-black text-indigo-700">€ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($order->notes)
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-bold text-gray-700 uppercase text-xs mb-2">Note Agente</h3>
                    <p class="text-sm italic text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
