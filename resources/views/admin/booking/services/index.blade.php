<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestione Servizi Strutture</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Colonna Sinistra: Aggiungi Categoria -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                        <h3 class="font-bold text-gray-900 text-lg mb-6">Nuova Categoria</h3>
                        <form action="{{ route('admin.booking.services.store-category') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nome Categoria *</label>
                                <input type="text" name="nome" required placeholder="es. Servizi di ristorazione" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Icona</label>
                                <div x-data="{ selected: '🍽️' }" class="space-y-3">
                                    <input type="hidden" name="icona" :value="selected">
                                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl">
                                        <span class="text-3xl" x-text="selected"></span>
                                        <span class="text-sm text-gray-500">Icona selezionata</span>
                                    </div>
                                    <div class="grid grid-cols-8 gap-1">
                                        @php
                                            $icons = ['🍽️','🏊','🅿️','📶','🛏️','🚿','🏠','🌿','🎯','🔒','🚗','🧹','👶','🌐','♿','🏋️','💆','🎵','🍳','☕','🏖️','🧺','🌡️','📺','🛎️','🎿','🐾','🏇','⛵','🎭','🧖','🚭','❄️','🔥','🪴','🧊','🫧','🎲'];
                                        @endphp
                                        @foreach($icons as $icon)
                                            <button type="button" @click="selected = '{{ $icon }}'" class="h-9 w-9 text-xl flex items-center justify-center rounded-lg hover:bg-indigo-100 transition-all cursor-pointer" :class="selected === '{{ $icon }}' ? 'bg-indigo-100 ring-2 ring-indigo-500' : 'bg-white border'">
                                                {{ $icon }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-all">
                                + Aggiungi Categoria
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Colonna Destra: Lista Categorie + Servizi -->
                <div class="lg:col-span-2 space-y-6">
                    @if($categories->count() === 0)
                        <div class="bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200 p-12 text-center">
                            <p class="text-gray-400 text-lg">Nessuna categoria di servizi definita.</p>
                            <p class="text-gray-400 text-sm mt-2">Crea la prima categoria usando il form a sinistra.</p>
                        </div>
                    @endif

                    @foreach($categories as $category)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ editMode: false }">
                            <!-- Header Categoria -->
                            <div class="bg-gradient-to-r from-indigo-50 to-white p-5 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div x-show="!editMode" class="flex items-center gap-3">
                                        <span class="text-2xl">{{ $category->icona }}</span>
                                        <h3 class="font-extrabold text-gray-900 text-lg">{{ $category->nome }}</h3>
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">{{ $category->services->count() }} servizi</span>
                                    </div>

                                    <!-- Edit Form (hidden by default) -->
                                    <form x-show="editMode" action="{{ route('admin.booking.services.update-category', $category) }}" method="POST" class="flex items-center gap-2 flex-1">
                                        @csrf
                                        @method('PUT')
                                        <div x-data="{ editIcon: '{{ $category->icona }}' }" class="flex items-center gap-2 flex-1">
                                            <div class="relative" x-data="{ showPicker: false }">
                                                <button type="button" @click="showPicker = !showPicker" class="text-2xl h-10 w-10 flex items-center justify-center rounded-lg border bg-white hover:bg-gray-50">
                                                    <span x-text="editIcon"></span>
                                                </button>
                                                <div x-show="showPicker" @click.away="showPicker = false" class="absolute top-12 left-0 z-50 bg-white border rounded-xl shadow-xl p-3 grid grid-cols-8 gap-1 w-80">
                                                    @foreach($icons as $icon)
                                                        <button type="button" @click="editIcon = '{{ $icon }}'; showPicker = false" class="h-8 w-8 text-lg flex items-center justify-center rounded hover:bg-indigo-100">{{ $icon }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <input type="hidden" name="icona" :value="editIcon">
                                            <input type="text" name="nome" value="{{ $category->nome }}" class="flex-1 rounded-lg border-gray-200 text-sm font-bold" required>
                                        </div>
                                        <button type="submit" class="bg-green-600 text-white text-xs font-bold py-2 px-3 rounded-lg">Salva</button>
                                        <button type="button" @click="editMode = false" class="text-gray-400 text-xs font-bold py-2 px-3">Annulla</button>
                                    </form>

                                    <div x-show="!editMode" class="flex items-center gap-2">
                                        <button type="button" @click="editMode = true" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold py-1 px-2 rounded hover:bg-indigo-50">
                                            ✏️ Modifica
                                        </button>
                                        <form action="{{ route('admin.booking.services.destroy-category', $category) }}" method="POST" onsubmit="return confirm('Eliminare questa categoria e tutti i suoi servizi?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold py-1 px-2 rounded hover:bg-red-50">
                                                🗑️ Elimina
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista Servizi -->
                            <div class="p-5 space-y-2">
                                @foreach($category->services as $service)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl group hover:bg-indigo-50 transition-all">
                                        <div class="flex items-center gap-2">
                                            <span class="text-indigo-500 text-sm">✓</span>
                                            <span class="text-sm font-medium text-gray-800">{{ $service->nome }}</span>
                                        </div>
                                        <form action="{{ route('admin.booking.services.destroy-service', $service) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-xs" onclick="return confirm('Eliminare questo servizio?')">✕</button>
                                        </form>
                                    </div>
                                @endforeach

                                @if($category->services->count() === 0)
                                    <p class="text-center text-gray-400 text-xs italic py-2">Nessun servizio in questa categoria.</p>
                                @endif

                                <!-- Aggiungi Servizio -->
                                <form action="{{ route('admin.booking.services.store-service', $category) }}" method="POST" class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                                    @csrf
                                    <input type="text" name="nome" required placeholder="Nuovo servizio..." class="flex-1 text-sm rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition-all">
                                        + Aggiungi
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
