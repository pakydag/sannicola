<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Struttura Shop - Tags') }}
            </h2>
            <a href="{{ route('admin.shop.tags.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nuovo Tag
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
                                    <th class="py-2 px-4 border-b text-left w-1/2">Nome Tag</th>
                                    <th class="py-2 px-4 border-b text-left w-1/4">Slug</th>
                                    <th class="py-2 px-4 border-b text-right w-1/4">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $t)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b font-medium">{{ $t->nome }}</td>
                                        <td class="py-2 px-4 border-b text-gray-500">{{ $t->slug }}</td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.shop.tags.edit', $t) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            <form action="{{ route('admin.shop.tags.destroy', $t) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro di voler eliminare questo Tag?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-gray-500">Nessun tag configurato al momento.</td>
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
