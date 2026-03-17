<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Modifica Condizione di Pagamento B2B</h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-2xl">
                <div class="p-6">
                    <form action="{{ route('admin.b2b.payment-conditions.update', $paymentCondition) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome Condizione</label>
                                <input type="text" name="name" value="{{ old('name', $paymentCondition->name) }}" required placeholder="es. Bonifico 30gg fine mese" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descrizione (opzionale)</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $paymentCondition->description) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t flex justify-end">
                            <a href="{{ route('admin.b2b.payment-conditions.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 px-4 py-2">Annulla</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md shadow-sm">
                                Aggiorna Condizione
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
