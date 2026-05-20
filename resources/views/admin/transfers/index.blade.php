<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Richieste Transfer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Ricezione</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome e Cognome</th>
                                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Transfer</th>
                                    <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Azione</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($requests as $richiesta)
                                    <tr class="hover:bg-gray-50 {{ !$richiesta->letto ? 'bg-indigo-50 font-semibold' : '' }}">
                                        <td class="py-4 px-6 text-sm">
                                            @if(!$richiesta->letto)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Nuovo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Letto
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500">
                                            {{ $richiesta->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-900">
                                            {{ $richiesta->nome }} {{ $richiesta->cognome }}
                                            <br><a href="mailto:{{ $richiesta->email }}" class="text-xs text-indigo-600 hover:text-indigo-900">{{ $richiesta->email }}</a>
                                            <br><span class="text-xs text-gray-500">{{ $richiesta->telefono }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500">
                                            <strong>Verso:</strong> {{ $richiesta->luogo_arrivo }}<br>
                                            <strong>Il:</strong> {{ \Carbon\Carbon::parse($richiesta->data)->format('d/m/Y') }} {{ $richiesta->orario }}
                                            @if($richiesta->andata_ritorno)
                                                <br><span class="text-xs text-indigo-600 font-semibold">Andata/Ritorno</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm text-right space-x-2">
                                            <a href="{{ route('admin.transfers.show', $richiesta) }}" class="text-indigo-600 hover:text-indigo-900">Leggi</a>
                                            
                                            <form action="{{ route('admin.transfers.destroy', $richiesta) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare questa richiesta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-6 text-sm text-center text-gray-500">
                                            Nessuna richiesta transfer ricevuta finora.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
