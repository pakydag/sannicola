<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking - Archivio Clienti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex justify-between items-center">
                        <form action="{{ route('admin.booking.customers.index') }}" method="GET" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca cliente..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-all">Cerca</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">Nome e Cognome</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-center">Telefono</th>
                                    <th class="py-2 px-4 border-b text-center">Nazione</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-sm text-gray-500">#{{ $c->id }}</td>
                                        <td class="py-2 px-4 border-b font-bold">{{ $c->nome }} {{ $c->cognome }}</td>
                                        <td class="py-2 px-4 border-b">{{ $c->email }}</td>
                                        <td class="py-2 px-4 border-b text-center text-sm">{{ $c->telefono ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b text-center text-sm">{{ $c->nazione ?? '-' }}</td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.booking.customers.show', $c) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Dettaglio</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="py-4 px-4 text-center text-gray-500">Nessun cliente trovato in questo archivio.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
