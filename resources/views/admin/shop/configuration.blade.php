<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configurazione Shop</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.shop.configuration.update') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-8">
                            <div class="border-b border-gray-100 pb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Disponibilità e Magazzino</h3>
                                <p class="text-sm text-gray-500 mb-6">Gestisci come il sistema deve trattare le quantità dei prodotti.</p>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="shop_stock_infinite" name="shop_stock_infinite" type="checkbox" value="1" {{ ($settings['shop_stock_infinite'] ?? '0') == '1' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="shop_stock_infinite" class="font-bold text-gray-700">Disponibilità Infinita</label>
                                        <p class="text-gray-500">Se attivo, i prodotti non verranno mai mostrati come esauriti e il controllo delle quantità nel carrello verrà ignorato.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-b border-gray-100 pb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Visualizzazione Prezzi</h3>
                                <p class="text-sm text-gray-500 mb-6">Gestisci la visibilità dei prezzi negli elenchi prodotti.</p>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="shop_price_preview" name="shop_price_preview" type="checkbox" value="1" {{ ($settings['shop_price_preview'] ?? '0') == '1' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="shop_price_preview" class="font-bold text-gray-700">Prezzi in Anteprima</label>
                                        <p class="text-gray-500">Se attivo, mostra il prezzo (o il prezzo più basso tra le varianti) direttamente nella griglia dei prodotti prima di entrare nel dettaglio.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg hover:shadow-indigo-200">
                                Salva Configurazione
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
