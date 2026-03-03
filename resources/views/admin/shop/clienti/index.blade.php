<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shop B2B - Clienti') }}
            </h2>
            <a href="{{ route('admin.shop.clienti.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition">
                + Nuovo Cliente
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
                                    <th class="py-2 px-4 border-b text-left">Nome e Cognome</th>
                                    <th class="py-2 px-4 border-b text-left">Ragione Sociale</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-left">Provincia</th>
                                    <th class="py-2 px-4 border-b text-center">Attivo</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clienti as $c)
                                    <tr>
                                        <td class="py-2 px-4 border-b font-bold">{{ $c->cognome }} {{ $c->nome }}</td>
                                        <td class="py-2 px-4 border-b">{{ $c->ragione_sociale ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $c->email }}</td>
                                        <td class="py-2 px-4 border-b">{{ $c->provincia ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($c->attivo)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.shop.clienti.edit', $c) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            <form action="{{ route('admin.shop.clienti.destroy', $c) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="py-4 px-4 text-center text-gray-500">Nessun cliente registrato.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
