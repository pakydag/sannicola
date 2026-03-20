<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Scheda Cliente: {{ $customer->nome }} {{ $customer->cognome }}
            </h2>
            <a href="{{ route('admin.booking.customers.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Torna all'elenco</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Personali -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Anagrafica</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Email</p>
                                <p class="font-bold">{{ $customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Telefono</p>
                                <p class="font-bold">{{ $customer->telefono ?? 'Non fornito' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Città</p>
                                <p class="font-bold">{{ $customer->citta ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nazione</p>
                                <p class="font-bold">{{ $customer->nazione ?? '-' }}</p>
                            </div>
                            <div class="pt-4 mt-4 border-t">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $customer->attivo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->attivo ? 'ACCOUNT ATTIVO' : 'DISATTIVATO' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Storico Prenotazioni -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Storico Prenotazioni</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">ID</th>
                                        <th class="py-2 px-4 border-b text-left">Struttura</th>
                                        <th class="py-2 px-4 border-b text-center">Periodo</th>
                                        <th class="py-2 px-4 border-b text-right">Totale</th>
                                        <th class="py-2 px-4 border-b text-right">Azione</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->bookings as $b)
                                        <tr>
                                            <td class="py-2 px-4 border-b text-xs text-gray-400">#{{ $b->id }}</td>
                                            <td class="py-2 px-4 border-b font-bold">{{ $b->structure->nome ?? 'N/D' }}</td>
                                            <td class="py-2 px-4 border-b text-center text-xs">
                                                {{ \Carbon\Carbon::parse($b->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($b->end_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-2 px-4 border-b text-right font-bold text-indigo-600">
                                                €{{ number_format($b->totale_prezzo, 2, ',', '.') }}
                                            </td>
                                            <td class="py-2 px-4 border-b text-right">
                                                <a href="{{ route('admin.booking.bookings.show', $b) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">Dettaglio</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="py-4 text-center text-gray-500 italic">Nessuna prenotazione trovata per questo cliente.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
