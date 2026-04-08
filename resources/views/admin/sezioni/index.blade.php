<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestione Sezioni') }}
            </h2>
            <a href="{{ route('admin.sezioni.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nuova Sezione
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left w-16">Ordine</th>
                                    <th class="py-2 px-4 border-b text-left">Nome Sezione</th>
                                    <th class="py-2 px-4 border-b text-center w-24">Tipo</th>
                                    <th class="py-2 px-4 border-b text-center w-24">Visibile</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-sections">
                                @forelse($sezioni as $sezione)
                                    <tr data-id="{{ $sezione->id }}" class="sortable-row bg-white">
                                        <td class="py-2 px-4 border-b text-center cursor-move text-gray-400 hover:text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        </td>
                                        <td class="py-2 px-4 border-b font-bold">
                                            {{ $sezione->nome }}
                                            @if($sezione->modulo)
                                                <span class="ml-2 px-2 inline-flex text-[10px] leading-4 font-bold rounded bg-gray-100 text-gray-500 uppercase tracking-tighter border border-gray-200">Modulo: {{ strtoupper($sezione->modulo) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($sezione->modulo)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-gray-600 text-white">Sistema</span>
                                            @elseif($sezione->tipo == 'pagina')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800">Pagina</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-purple-100 text-purple-800">Archivio</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            @if($sezione->visibile)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sì</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.sezioni.edit', $sezione) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            @if(!$sezione->modulo)
                                                <form action="{{ route('admin.sezioni.destroy', $sezione) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare questa sezione?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center text-gray-500">Nessuna sezione trovata.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Include SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('sortable-sections');
            if (el) {
                var sortable = Sortable.create(el, {
                    handle: '.cursor-move',
                    animation: 150,
                    ghostClass: 'bg-indigo-50',
                    onEnd: function (evt) {
                        let order = [];
                        document.querySelectorAll('#sortable-sections .sortable-row').forEach(function(row, index) {
                            order.push({
                                id: row.getAttribute('data-id'),
                                position: index
                            });
                        });

                        // AJAX Request to save new order
                        fetch("{{ route('admin.sezioni.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                // Optional: toast notification
                                console.log('Ordine salvato');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
</x-app-layout>
