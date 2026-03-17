<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica Agente: ') }} {{ $agent->name }} {{ $agent->surname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.b2b.agents.update', $agent) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informazioni Personali</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $agent->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label for="surname" class="block text-sm font-medium text-gray-700">Cognome</label>
                                        <input type="text" name="surname" id="surname" value="{{ old('surname', $agent->surname) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $agent->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" required>
                                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefono</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $agent->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Autorizzazioni Brand</h3>
                                <p class="text-sm text-gray-600 mb-4">L'agente potrà visualizzare ed ordinare solo i prodotti dei marchi selezionati.</p>
                                
                                <div class="space-y-2 max-h-48 overflow-y-auto p-4 border border-gray-200 rounded-md bg-gray-50 mb-6">
                                    @php $assignedBrands = $agent->b2bBrands->pluck('id')->toArray(); @endphp
                                    @foreach($brands as $brand)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="brands[]" id="brand_{{ $brand->id }}" value="{{ $brand->id }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array($brand->id, old('brands', $assignedBrands)) ? 'checked' : '' }}>
                                            <label for="brand_{{ $brand->id }}" class="ml-2 text-sm text-gray-700">{{ $brand->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <h3 class="text-lg font-medium text-gray-900 mb-4">Clienti Autorizzati</h3>
                                <p class="text-sm text-gray-600 mb-4">L'agente potrà caricare ordini solo per i clienti selezionati.</p>
                                
                                <div class="space-y-2 max-h-48 overflow-y-auto p-4 border border-gray-200 rounded-md bg-gray-50">
                                    @php $assignedCustomers = $agent->b2bCustomers->pluck('id')->toArray(); @endphp
                                    @foreach($customers as $customer)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="customers[]" id="cust_{{ $customer->id }}" value="{{ $customer->id }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array($customer->id, old('customers', $assignedCustomers)) ? 'checked' : '' }}>
                                            <label for="cust_{{ $customer->id }}" class="ml-2 text-sm text-gray-700">{{ $customer->business_name }} ({{ $customer->vat_number }})</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.b2b.agents.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Annulla</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Salva Modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
