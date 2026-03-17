<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordini B2B Ricevuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <!-- Mobile View -->
                        <div class="md:hidden space-y-4">
                            @forelse($orders as $order)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $order->status == 'pending' ? 'bg-yellow-400' : ($order->status == 'confirmed' ? 'bg-green-400' : 'bg-red-400') }}"></div>
                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 leading-tight">Ordine #{{ $order->id }}</h3>
                                            <p class="text-[10px] text-gray-500 font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($order->status == 'pending')
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-[10px] uppercase font-bold">In attesa</span>
                                            @elseif($order->status == 'confirmed')
                                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-[10px] uppercase font-bold">Confermato</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-[10px] uppercase font-bold">Annullato</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="pl-2 space-y-1 mt-3">
                                        <p class="text-xs"><span class="text-gray-400">Cliente:</span> <span class="font-medium">{{ $order->customer->business_name }}</span></p>
                                        <p class="text-xs"><span class="text-gray-400">Agente:</span> <span class="font-medium text-indigo-600">{{ $order->agent->name }} {{ $order->agent->surname }}</span></p>
                                    </div>

                                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 pl-2">
                                        <p class="font-bold text-gray-900">€ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                        <a href="{{ route('admin.b2b.orders.edit', $order) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold rounded hover:bg-indigo-700 transition">
                                            GESTISCI
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-sm text-gray-500">Nessun ordine B2B ricevuto.</p>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <div class="hidden md:flex flex-col overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordine #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Totale</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-l-4 {{ $order->status == 'pending' ? 'border-yellow-400' : ($order->status == 'confirmed' ? 'border-green-400' : 'border-red-400') }}">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->agent->name }} {{ $order->agent->surname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->customer->business_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">€ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($order->status == 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs uppercase font-bold">In attesa</span>
                                            @elseif($order->status == 'confirmed')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs uppercase font-bold">Confermato</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs uppercase font-bold">Annullato</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.b2b.orders.edit', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Gestisci/Modifica</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nessun ordine B2B ricevuto.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
