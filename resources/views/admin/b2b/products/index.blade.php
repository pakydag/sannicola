<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Inventario Prodotti B2B') }}
            </h2>
            <a href="{{ route('admin.b2b.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuovo Prodotto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form di Ricerca -->
                    <div class="mb-6">
                        <form action="{{ route('admin.b2b.products.index') }}" method="GET" class="flex gap-2">
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cerca per nome prodotto o brand..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cerca
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('admin.b2b.products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                                    Annulla
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden space-y-4">
                        @forelse($products as $product)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm relative overflow-hidden">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 leading-tight">{{ $product->name }}</h3>
                                        <p class="text-xs text-indigo-600 font-semibold">{{ $product->brand->name }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.b2b.products.edit', $product) }}" class="text-indigo-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.b2b.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Sicuro?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <div class="text-xs">
                                        <p class="text-gray-400">Stagione</p>
                                        <p class="font-medium text-gray-700">{{ $product->season ?? '-' }}</p>
                                    </div>
                                    <div class="text-xs text-right">
                                        <p class="text-gray-400">Prezzo</p>
                                        <p class="font-bold text-gray-900 text-sm">€ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs">
                                    @if($product->has_stock)
                                        <div class="flex items-center justify-between bg-indigo-50 p-2 rounded">
                                            <span class="text-indigo-700 font-bold">Magazzino: {{ $product->variants->sum('quantity') }} pz</span>
                                            <span class="text-gray-500 italic">{{ $product->variants->count() }} varianti</span>
                                        </div>
                                    @else
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-[10px] font-bold">SENZA MAGAZZINO</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500">{{ !empty($search) ? 'Nessun prodotto trovato.' : 'Nessun prodotto in inventario.' }}</p>
                        @endforelse
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:flex flex-col overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodotto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marchio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagione</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prezzo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Varianti / Stock</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->brand->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->season ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">€ {{ number_format($product->price, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($product->has_stock)
                                                <div class="space-y-1">
                                                    @php $totalStock = $product->variants->sum('quantity'); @endphp
                                                    <span class="font-bold text-indigo-600">Tot: {{ $totalStock }} pz</span>
                                                    <div class="text-xs text-gray-400">
                                                        {{ $product->variants->count() }} varianti registrate
                                                    </div>
                                                </div>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Senza Magazzino</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.b2b.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Modifica</a>
                                            <form action="{{ route('admin.b2b.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Sei sicuro di voler eliminare questo prodotto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ !empty($search) ? 'Nessun prodotto corrisponde alla ricerca.' : 'Nessun prodotto in inventario.' }}
                                        </td>
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
