<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Booking - Strutture') }}
            </h2>
            <a href="{{ route('admin.booking.structures.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition">
                + Nuova Struttura
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
                                    <th class="py-2 px-4 border-b text-center w-24">Foto</th>
                                    <th class="py-2 px-4 border-b text-left">Nome</th>
                                    <th class="py-2 px-4 border-b text-center">Bagni/Camere</th>
                                    <th class="py-2 px-4 border-b text-center">Posti</th>
                                    <th class="py-2 px-4 border-b text-right">Costo/Giorno</th>
                                    <th class="py-2 px-4 border-b text-center">Attivo</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($structures as $s)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($s->photos->count() > 0)
                                                <img src="{{ asset($s->photos->first()->path) }}" alt="Foto" class="h-12 w-16 object-cover rounded shadow-sm mx-auto">
                                            @else
                                                <div class="h-12 w-16 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs mx-auto">N/D</div>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b font-bold">{{ $s->nome }}</td>
                                        <td class="py-2 px-4 border-b text-center text-sm">
                                            {{ $s->bagni }} 🚿 / {{ $s->camere_letto }} 🛏️
                                        </td>
                                        <td class="py-2 px-4 border-b text-center text-sm">
                                            {{ $s->posti_totali }} 👤
                                        </td>
                                        <td class="py-2 px-4 border-b text-right font-mono">
                                            {{ $s->tipo_prezzo === 'fisso' ? 'Fisso' : 'Per persona' }}
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($s->attivo)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Si</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.booking.bookings.block', ['structure_id' => $s->id]) }}" class="text-amber-600 hover:text-amber-900 mr-3">Blocca Date</a>
                                            <a href="{{ route('admin.booking.structures.edit', $s) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            <form action="{{ route('admin.booking.structures.destroy', $s) }}" method="POST" class="inline-block" onsubmit="return confirm('Sicuro di voler eliminare questa struttura?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="py-4 px-4 text-center text-gray-500">Nessuna struttura creata.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
