<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione Clienti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca per nome, cognome o email..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Cerca</button>
                            @if(request('search'))
                                <a href="{{ route('admin.customers.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Resetta</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nome</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-left">Località</th>
                                    <th class="py-2 px-4 border-b text-center">Prenotazioni</th>
                                    <th class="py-2 px-4 border-b text-center">Ordini Shop</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b font-bold">{{ $customer->nome }} {{ $customer->cognome }}</td>
                                        <td class="py-2 px-4 border-b">{{ $customer->email }}</td>
                                        <td class="py-2 px-4 border-b text-sm text-gray-500">{{ $customer->citta }} ({{ $customer->nazione }})</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-bold">{{ $customer->bookings->count() }}</span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-bold">{{ $customer->orders->count() }}</span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Dettaglio</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="py-4 px-4 text-center text-gray-500">Nessun cliente trovato.</td></tr>
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
