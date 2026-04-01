<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuovo Reparto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form action="{{ route('admin.departments.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome Reparto</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" required placeholder="Es. Assistenza Software">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email di Notifica</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" required placeholder="es. assistenza@azienda.it">
                                <p class="text-xs text-gray-400 mt-1">Questa email riceverà una notifica per ogni nuovo ticket.</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Durata Appuntamento (minuti)</label>
                                <input type="number" name="appointment_duration" value="{{ old('appointment_duration', 30) }}" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" required min="5" step="5">
                                <p class="text-xs text-gray-400 mt-1">Durata predefinita per ogni slot in agenda.</p>
                            </div>
                        </div>

                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Orari di Lavoro e Disponibilità Vanessa</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-gray-600 text-xs font-bold mb-1">Ora Inizio (es. 09:00)</label>
                                    <input type="time" name="working_hours[start]" value="{{ old('working_hours.start', '09:00') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-600 text-xs font-bold mb-1">Ora Fine (es. 18:00)</label>
                                    <input type="time" name="working_hours[end]" value="{{ old('working_hours.end', '18:00') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm focus:outline-none">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <label class="inline-flex items-center mt-3">
                                        <input type="checkbox" name="working_hours[days][]" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-xs text-gray-600">Lun - Ven</span>
                                    </label>
                                    <label class="inline-flex items-center mt-1">
                                        <input type="checkbox" name="working_hours[days][]" value="6" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-xs text-gray-600">Sabato</span>
                                    </label>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-3 italic">Vanessa userà questi orari per verificare la tua disponibilità prima di confermare un appuntamento.</p>
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900">Reparto Attivo</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.departments.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm font-medium">Annulla</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-8 rounded shadow focus:outline-none focus:shadow-outline transition-colors">
                                Salva Reparto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
