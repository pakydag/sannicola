<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestione Articoli') }}
            </h2>
            <a href="{{ route('admin.articoli.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nuovo Articolo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">

                    <!-- Filtri e Ricerca -->
                    <form method="GET" action="{{ route('admin.articoli.index') }}" class="mb-6 flex flex-col md:flex-row gap-4 items-end bg-gray-50 p-4 rounded-lg">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block text-sm font-medium text-gray-700">Cerca nel titolo o sottotitolo</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cerca parole..." onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="w-full md:w-1/3">
                            <label for="section_id" class="block text-sm font-medium text-gray-700">Filtra per Sezione</label>
                            <select name="section_id" id="section_id" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Tutte le sezioni --</option>
                                @foreach($sezioni as $sezione)
                                    <option value="{{ $sezione->id }}" {{ request('section_id') == $sezione->id ? 'selected' : '' }}>{{ $sezione->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-4 w-full md:w-1/3 items-center mt-6 md:mt-0">
                            <x-primary-button>
                                {{ __('Filtra / Cerca') }}
                            </x-primary-button>
                            <a href="{{ route('admin.articoli.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Reset
                            </a>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left w-16">Ordine</th>
                                    <th class="py-2 px-4 border-b text-left">Foto</th>
                                    <th class="py-2 px-4 border-b text-left">Sezione</th>
                                    <th class="py-2 px-4 border-b text-left">Stato</th>
                                    <th class="py-2 px-4 border-b text-left">Titolo</th>
                                    <th class="py-2 px-4 border-b text-left">Sottotitolo</th>
                                    <th class="py-2 px-4 border-b text-left">Allegato</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-articles">
                                @forelse($articoli as $articolo)
                                    <tr data-id="{{ $articolo->id }}" class="sortable-row bg-white">
                                        <td class="py-2 px-4 border-b text-center cursor-move text-gray-400 hover:text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            @if($articolo->hasMedia('foto'))
                                                <img src="{{ $articolo->getFirstMediaUrl('foto', 'thumb') }}" alt="Foto" class="w-16 h-16 object-cover rounded">
                                            @elseif($articolo->foto)
                                                <img src="{{ asset($articolo->foto) }}" alt="Foto" class="w-16 h-16 object-cover rounded opacity-50">
                                            @else
                                                <span class="text-gray-400 italic">Nessuna</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-sm">
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $articolo->section ? $articolo->section->nome : 'Nessuna' }}</span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-sm">
                                            @if($articolo->visibile)
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold text-xs">PUBBLICATO</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded font-semibold text-xs">NASCOSTO</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b font-medium">{{ $articolo->titolo }}</td>
                                        <td class="py-2 px-4 border-b">{{ Str::limit($articolo->sottotitolo, 30) }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($articolo->hasMedia('allegati'))
                                                <a href="{{ $articolo->getFirstMediaUrl('allegati') }}" target="_blank" class="text-blue-500 hover:underline">Vedi File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <a href="{{ route('admin.articoli.edit', $articolo) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                            <form action="{{ route('admin.articoli.destroy', $articolo) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare questo articolo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center text-gray-500">Nessun articolo trovato.</td>
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
            var el = document.getElementById('sortable-articles');
            if (el) {
                var sortable = Sortable.create(el, {
                    handle: '.cursor-move',
                    animation: 150,
                    ghostClass: 'bg-indigo-50',
                    onEnd: function (evt) {
                        let order = [];
                        document.querySelectorAll('#sortable-articles .sortable-row').forEach(function(row, index) {
                            order.push({
                                id: row.getAttribute('data-id'),
                                position: index
                            });
                        });

                        // AJAX Request to save new order
                        fetch("{{ route('admin.articoli.reorder') }}", {
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
