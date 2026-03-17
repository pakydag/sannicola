<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica Amministratore') }}: {{ $user->name }} {{ $user->surname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Cognome -->
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700">Cognome</label>
                                <input type="text" name="surname" id="surname" value="{{ old('surname', $user->surname) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('surname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required {{ $user->email === 'admin@admin.com' ? 'readonly' : '' }} class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $user->email === 'admin@admin.com' ? 'bg-gray-100' : '' }}">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Permessi -->
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-semibold text-gray-900 border-b pb-2 mb-4">Ruolo e Permessi</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_super_admin" name="is_super_admin" type="checkbox" value="1" {{ old('is_super_admin', $user->is_super_admin) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_super_admin" class="font-medium text-gray-700">Super Amministratore</label>
                                            <p class="text-gray-500">Può gestire altri amministratori e tutte le sezioni.</p>
                                        </div>
                                    </div>

                                    <div class="space-y-2 pl-7 pt-2">
                                        <p class="text-xs font-bold text-gray-400 uppercase">Sezioni Visualizzabili</p>
                                        
                                        <div class="flex items-center">
                                            <input id="can_manage_site" name="can_manage_site" type="checkbox" value="1" {{ old('can_manage_site', $user->can_manage_site) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            <label for="can_manage_site" class="ml-2 text-sm text-gray-700">Sito (Articoli, Sezioni, Widget)</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="can_manage_shop" name="can_manage_shop" type="checkbox" value="1" {{ old('can_manage_shop', $user->can_manage_shop) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            <label for="can_manage_shop" class="ml-2 text-sm text-gray-700">Shop (Prodotti, Ordini, Clienti)</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="can_manage_booking" name="can_manage_booking" type="checkbox" value="1" {{ old('can_manage_booking', $user->can_manage_booking) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            <label for="can_manage_booking" class="ml-2 text-sm text-gray-700">Booking (Strutture, Prenotazioni)</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="can_manage_voip" name="can_manage_voip" type="checkbox" value="1" {{ old('can_manage_voip', $user->can_manage_voip) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            <label for="can_manage_voip" class="ml-2 text-sm text-gray-700">Agente AI (Voip)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                                Annulla
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Aggiorna Amministratore
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
