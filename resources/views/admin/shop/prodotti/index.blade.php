<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shop B2B - Prodotti') }}
            </h2>
            <a href="{{ route('admin.shop.prodotti.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition">
                + Nuovo Prodotto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <form action="{{ route('admin.shop.prodotti.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full max-w-2xl">
                            <div class="flex flex-1 min-w-[240px]">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca per nome, SKU, marca, tag..." class="shadow-sm border-gray-300 rounded-l-md w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button type="submit" class="bg-gray-100 border border-l-0 border-gray-300 text-gray-700 px-4 py-2 rounded-r-md hover:bg-gray-200 text-sm font-medium">Cerca</button>
                            </div>
                            <div class="w-auto">
                                <select name="tag" onchange="this.form.submit()" class="shadow-sm border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                                    <option value="">-- Filtra per Tag --</option>
                                    @foreach($tags as $t)
                                        <option value="{{ $t->id }}" {{ request('tag') == $t->id ? 'selected' : '' }}>
                                            #{{ $t->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(request('search') || request('tag'))
                                <a href="{{ route('admin.shop.prodotti.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">Azzera</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-center w-16">Foto</th>
                                    <th class="py-2 px-4 border-b text-left">SKU Padre</th>
                                    <th class="py-2 px-4 border-b text-left">Nome</th>
                                    <th class="py-2 px-4 border-b text-left">Categoria</th>
                                    <th class="py-2 px-4 border-b text-left">Marca</th>
                                    <th class="py-2 px-4 border-b text-center">Visibile</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prodotti as $p)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($p->foto_aggiuntive && is_array($p->foto_aggiuntive) && count($p->foto_aggiuntive) > 0 && !empty($p->foto_aggiuntive[0]))
                                                <img src="{{ url($p->foto_aggiuntive[0]) }}" alt="Foto" class="h-10 w-10 object-cover rounded shadow-sm mx-auto">
                                            @else
                                                <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs mx-auto">N/D</div>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-gray-500 text-sm">{{ $p->sku_padre ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="font-bold text-gray-900">{{ $p->nome }}</div>
                                            @if($p->tags && $p->tags->isNotEmpty())
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach($p->tags as $t)
                                                        <a href="{{ route('admin.shop.prodotti.index', ['tag' => $t->id]) }}" 
                                                           title="Filtra per il tag {{ $t->nome }}" 
                                                           class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-normal bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100 transition-colors">
                                                            #{{ $t->nome }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ $p->category->nome ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $p->marca ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($p->visibile)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.shop.prodotti.edit', $p) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            <form action="{{ route('admin.shop.prodotti.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="py-4 px-4 text-center text-gray-500">Nessun prodotto trovato.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $prodotti->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
