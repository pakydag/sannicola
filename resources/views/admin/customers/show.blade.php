<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dettaglio Cliente: {{ $customer->nome }} {{ $customer->cognome }}
            </h2>
            <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Torna all'elenco</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Profilo -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Informazioni Profilo</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-gray-400 font-bold uppercase text-[10px]">Email</p>
                                <p class="font-semibold">{{ $customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-bold uppercase text-[10px]">Telefono</p>
                                <p class="font-semibold">{{ $customer->telefono }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-bold uppercase text-[10px]">Nazione / Città</p>
                                <p class="font-semibold">{{ $customer->nazione }} / {{ $customer->citta }}</p>
                            </div>
                            <div class="pt-4 border-t">
                                <p class="text-gray-400 font-bold uppercase text-[10px]">Registrato il</p>
                                <p>{{ $customer->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cronologia Prenotazioni -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Cronologia Prenotazioni</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="py-2 text-left text-xs font-bold uppercase text-gray-400">ID</th>
                                        <th class="py-2 text-left text-xs font-bold uppercase text-gray-400">Struttura</th>
                                        <th class="py-2 text-center text-xs font-bold uppercase text-gray-400">Periodo</th>
                                        <th class="py-2 text-right text-xs font-bold uppercase text-gray-400">Totale</th>
                                        <th class="py-2 text-center text-xs font-bold uppercase text-gray-400">Stato</th>
                                        <th class="py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->bookings as $b)
                                        <tr class="border-b last:border-0">
                                            <td class="py-3">#{{ $b->id }}</td>
                                            <td class="py-3 font-semibold">{{ $b->structure->nome }}</td>
                                            <td class="py-3 text-center text-xs">
                                                {{ \Carbon\Carbon::parse($b->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($b->end_date)->format('d/m/y') }}
                                            </td>
                                            <td class="py-3 text-right font-bold">€{{ number_format($b->totale_prezzo, 2, ',', '.') }}</td>
                                            <td class="py-3 text-center">
                                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $b->stato == 'confermato' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ strtoupper($b->stato) }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-right text-xs">
                                                <a href="{{ route('admin.booking.bookings.show', $b) }}" class="text-indigo-600 hover:underline">Vedi</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-4 text-center text-gray-500 text-sm">Nessuna prenotazione trovata per questo cliente.</td></tr>
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
