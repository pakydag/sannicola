<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione Servizi Extra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Extra Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Aggiungi Nuovo Servizio Extra</h3>
                    <form action="{{ route('admin.booking.extras.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nome Servizio</label>
                                <input type="text" name="nome" placeholder="es. Culla, Colazione..." class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Prezzo Giornaliero (€)</label>
                                <input type="number" step="0.01" name="prezzo" placeholder="0.00" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ordine</label>
                                <input type="number" name="ordine" value="0" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Salva Servizio Extra
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Extras -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Servizi Extra Esistenti</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordine</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prezzo/Giorno</th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($extras as $extra)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $extra->ordine }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $extra->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($extra->prezzo, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2" x-data="{ editing: false }">
                                                <!-- Edit Form (Modal or Inline would be better, using simple for now) -->
                                                <button @click="editing = !editing" class="text-indigo-600 hover:text-indigo-900 font-bold">Modifica</button>
                                                
                                                <form action="{{ route('admin.booking.extras.destroy', $extra) }}" method="POST" onsubmit="return confirm('Sei sicuro?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Elimina</button>
                                                </form>

                                                <!-- Inline Edit Template -->
                                                <template x-if="editing">
                                                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                                        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl" @click.away="editing = false">
                                                            <h4 class="text-xl font-bold mb-4">Modifica {{ $extra->nome }}</h4>
                                                            <form action="{{ route('admin.booking.extras.update', $extra) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label class="block text-gray-700 text-sm font-bold mb-1">Nome</label>
                                                                        <input type="text" name="nome" value="{{ $extra->nome }}" class="w-full border rounded p-2" required>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-gray-700 text-sm font-bold mb-1">Prezzo/Giorno</label>
                                                                        <input type="number" step="0.01" name="prezzo" value="{{ $extra->prezzo }}" class="w-full border rounded p-2" required>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-gray-700 text-sm font-bold mb-1">Ordine</label>
                                                                        <input type="number" name="ordine" value="{{ $extra->ordine }}" class="w-full border rounded p-2">
                                                                    </div>
                                                                </div>
                                                                <div class="mt-6 flex justify-end gap-2">
                                                                    <button type="button" @click="editing = false" class="px-4 py-2 text-gray-500 font-bold">Annulla</button>
                                                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold">Salva Modifiche</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Nessun servizio extra definito.</td>
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
