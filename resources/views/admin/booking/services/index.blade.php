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

                <!-- Colonna Sinistra: Aggiungi Servizio Principale -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                        <h3 class="font-bold text-gray-900 text-lg mb-6">Nuovo Servizio Principale</h3>
                        <form action="{{ route('admin.booking.services.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nome Servizio *</label>
                                <input type="text" name="nome" required placeholder="es. Ristorante, Piscina Aperta..." class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Icona</label>
                                <div x-data="{ selected: '🍽️' }" class="space-y-3">
                                    <input type="hidden" name="icona" :value="selected">
                                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <span class="text-3xl" x-text="selected"></span>
                                        <span class="text-sm font-medium text-gray-500">Icona selezionata</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 p-2 bg-gray-50 rounded-xl border border-gray-100">
                                        @php
                                            $icons = ['🍽️','🏊','🅿️','📶','🛏️','🚿','🏠','🌿','🎯','🔒','🚗','🧹','👶','🌐','♿','🏋️','💆','🎵','🍳','☕','🏖️','🧺','🌡️','📺','🛎️','🎿','🐾','🏇','⛵','🎭','🧖','🚭','❄️','🔥','🪴','🧊','🫧','🎲'];
                                        @endphp
                                        @foreach($icons as $icon)
                                            <button type="button" @click="selected = '{{ $icon }}'" class="h-8 w-8 text-lg flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm transition-all focus:outline-none" :class="selected === '{{ $icon }}' ? 'bg-white shadow-sm ring-2 ring-indigo-500 z-10' : 'text-gray-600'">
                                                {{ $icon }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-4 rounded-xl shadow transition-all transform active:scale-95">
                                + Aggiungi Servizio
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Colonna Destra: Elenco Servizi -->
                <div class="lg:col-span-2 space-y-4">
                    @if($services->count() === 0)
                        <div class="bg-white rounded-3xl shadow-sm border-2 border-dashed border-gray-200 p-12 text-center h-full flex flex-col justify-center items-center">
                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Nessun servizio definito.</h3>
                            <p class="text-gray-500 text-sm">Inizia ad aggiungere servizi usando il form laterale. Meno click, più prenotazioni!</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($services as $service)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-visible relative group" x-data="{ editMode: false }">
                                <!-- Vista Normale -->
                                <div x-show="!editMode" class="p-5 flex items-center gap-4 transition-transform hover:scale-[1.02]">
                                    <div class="h-14 w-14 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-3xl shadow-inner shrink-0">
                                        {{ $service->icona ?: '✨' }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-extrabold text-gray-900 text-lg">{{ $service->nome }}</h3>
                                    </div>
                                    
                                    <!-- Azioni Hover -->
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex flex-col gap-1 absolute right-2 top-2 bg-white/90 backdrop-blur rounded-lg p-1 shadow-sm border border-gray-100">
                                        <button type="button" @click="editMode = true" class="text-gray-400 hover:text-indigo-600 p-1.5 rounded-md hover:bg-indigo-50 focus:outline-none" title="Modifica">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                        <form action="{{ route('admin.booking.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Eliminare definitivamente questo servizio? Tutte le strutture collegate non mostreranno più questa voce.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-md hover:bg-red-50 focus:outline-none" title="Elimina">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Pannello Modifica -->
                                <div x-show="editMode" class="p-4 bg-indigo-50/50 rounded-2xl relative z-20">
                                    <form action="{{ route('admin.booking.services.update', $service) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div x-data="{ editIcon: '{{ $service->icona ?: '✨' }}', showPicker: false }" class="relative flex flex-col gap-3">
                                            <div class="flex gap-2 w-full">
                                                <button type="button" @click="showPicker = !showPicker" class="h-10 w-10 shrink-0 flex items-center justify-center text-xl bg-white border border-gray-200 rounded-lg shadow-sm hover:border-indigo-300">
                                                    <span x-text="editIcon"></span>
                                                </button>
                                                <input type="hidden" name="icona" :value="editIcon">
                                                <input type="text" name="nome" value="{{ $service->nome }}" class="flex-1 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold shadow-sm" required placeholder="Nome servizio">
                                            </div>

                                            <div x-show="showPicker" @click.away="showPicker = false" class="absolute top-12 left-0 z-[100] bg-white border border-gray-200 rounded-xl shadow-xl p-3 flex flex-wrap gap-2 w-72 max-h-48 overflow-y-auto">
                                                @foreach($icons as $icon)
                                                    <button type="button" @click="editIcon = '{{ $icon }}'; showPicker = false" class="h-8 w-8 text-lg flex items-center justify-center rounded hover:bg-gray-100 hover:scale-110 transition-transform">{{ $icon }}</button>
                                                @endforeach
                                            </div>
                                            
                                            <div class="flex items-center gap-2 mt-2">
                                                <button type="button" @click="editMode = false" class="flex-1 py-1 px-3 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Annulla</button>
                                                <button type="submit" class="flex-1 py-1 px-3 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm">Salva</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
