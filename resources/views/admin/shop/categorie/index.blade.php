<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shop B2B - Categorie') }}
            </h2>
            <a href="{{ route('admin.shop.categorie.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition">
                + Nuova Categoria
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
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nome</th>
                                    <th class="py-2 px-4 border-b text-left">Categoria Padre</th>
                                    <th class="py-2 px-4 border-b text-center">Visibile</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Group categories by parent to simulate a tree
                                    $grouped = $categorie->groupBy('parent_id');
                                    $mainCategories = $grouped->get('') ?? collect(); // Root categories (parent_id is null)
                                    if ($mainCategories->isEmpty() && $categorie->count() > 0) {
                                        // Fallback if there are no root categories but there are categories
                                        $mainCategories = $categorie->whereNull('parent_id');
                                    }
                                @endphp

                                @foreach($mainCategories as $cat)
                                    <!-- Categoria Principale -->
                                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                                        <td class="py-2 px-4 border-b font-bold text-lg text-indigo-700">{{ $cat->nome }}</td>
                                        <td class="py-2 px-4 border-b text-gray-500 text-sm">Principale</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($cat->visibile)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.shop.categorie.edit', $cat) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 text-sm">Modifica</a>
                                            <form action="{{ route('admin.shop.categorie.destroy', $cat) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro? Eliminare una categoria padre elimina anche le dipendenze se configurate.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                    <!-- Sottocategorie -->
                                    @foreach($grouped->get($cat->id) ?? [] as $sub)
                                        <tr class="bg-white">
                                            <td class="py-2 px-4 border-b pl-10 font-medium text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                {{ $sub->nome }}
                                            </td>
                                            <td class="py-2 px-4 border-b text-gray-400 text-xs">Figlia di {{ $cat->nome }}</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                @if($sub->visibile)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b text-right">
                                                <a href="{{ route('admin.shop.categorie.edit', $sub) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 text-sm">Modifica</a>
                                                <form action="{{ route('admin.shop.categorie.destroy', $sub) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Elimina</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                
                                @if($categorie->isEmpty())
                                    <tr><td colspan="4" class="py-4 px-4 text-center text-gray-500">Nessuna categoria.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
