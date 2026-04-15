<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shop – Marche') }}
            </h2>
            <a href="{{ route('admin.shop.marche.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition">
                + Nuova Marca
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Logo</th>
                                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Nome Marca</th>
                                    <th class="py-2 px-4 border-b text-center text-xs font-semibold text-gray-600 uppercase">Prodotti</th>
                                    <th class="py-2 px-4 border-b text-center text-xs font-semibold text-gray-600 uppercase">Visibile</th>
                                    <th class="py-2 px-4 border-b text-right text-xs font-semibold text-gray-600 uppercase">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($marche as $marca)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b">
                                            @if($marca->foto)
                                                <img src="{{ $marca->foto }}" alt="{{ $marca->nome }}"
                                                     class="h-10 w-16 object-contain rounded border border-gray-200 bg-gray-50 p-0.5">
                                            @else
                                                <span class="inline-flex items-center justify-center h-10 w-16 bg-gray-100 rounded text-gray-400 text-xs">nessuna</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            <p class="font-semibold text-gray-800">{{ $marca->nome }}</p>
                                            @if($marca->descrizione)
                                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ $marca->descrizione }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                                {{ $marca->products()->count() }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            @if($marca->visibile)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b text-right">
                                            <a href="{{ route('admin.shop.marche.edit', $marca) }}"
                                               class="text-indigo-600 hover:text-indigo-900 mr-3 text-sm font-bold">Modifica</a>
                                            <form action="{{ route('admin.shop.marche.destroy', $marca) }}" method="POST"
                                                  class="inline-block"
                                                  onsubmit="return confirm('Sei sicuro di voler eliminare questa marca?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 text-sm font-bold">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                            Nessuna marca ancora creata.
                                            <a href="{{ route('admin.shop.marche.create') }}" class="text-indigo-600 hover:underline ml-1">Crea la prima marca →</a>
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
