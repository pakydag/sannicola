<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Widget Globali') }}
            </h2>
            <a href="{{ route('admin.global-widgets.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow">
                + Nuovo Widget Globale
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b py-4 px-6 bg-gray-50 font-bold text-gray-600 uppercase text-sm">Titolo</th>
                                    <th class="border-b py-4 px-6 bg-gray-50 font-bold text-gray-600 uppercase text-sm">Tipo</th>
                                    <th class="border-b py-4 px-6 bg-gray-50 font-bold text-gray-600 uppercase text-sm">Appare in...</th>
                                    <th class="border-b py-4 px-6 bg-gray-50 font-bold text-gray-600 text-right uppercase text-sm">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($widgets as $widget)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-b py-4 px-6 text-gray-800 font-medium">{{ $widget->titolo }}</td>
                                        <td class="border-b py-4 px-6">
                                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded uppercase font-bold">{{ $widget->tipo }}</span>
                                        </td>
                                        <td class="border-b py-4 px-6 text-sm text-gray-500">
                                            @php
                                                // Find how many regular widgets reference this global widget
                                                $count = \App\Models\Widget::where('tipo', 'global_widget')
                                                    ->where('data->global_widget_id', (string)$widget->id)
                                                    ->count();
                                                // Note: querying JSON this way is supported in MySQL 5.7+ and MariaDB.
                                            @endphp
                                            {{ $count }} articoli collegati
                                        </td>
                                        <td class="border-b py-4 px-6 text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.global-widgets.edit', $widget) }}" class="text-blue-500 hover:text-blue-700 flex items-center">
                                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Modifica
                                                </a>
                                                <form action="{{ route('admin.global-widgets.destroy', $widget) }}" method="POST" onsubmit="return confirm('Sei sicuro? Questo widget scomparirà da TUTTI gli articoli a cui è collegato.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Elimina
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border-b py-4 px-6 text-gray-500 text-center italic">Nessun widget globale trovato. Inizia creandone uno!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $widgets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
