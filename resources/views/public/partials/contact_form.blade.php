<div class="bg-white shadow-sm xl:p-14 p-8 overflow-hidden relative rounded-lg" id="contatti">
    <div class="text-center mb-8 hidden">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Contattaci</h3>
        <p class="text-gray-600">Compila il modulo sottostante per inviarci una richiesta. Ti risponderemo al più presto.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 mb-6 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-semibold text-lg">{{ session('success') }}</p>
            </div>
        </div>
    @else
        <form action="{{ route('public.contact.submit') }}#contatti" method="POST" class="max-w-3xl mx-auto">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Cognome -->
                <div>
                    <label for="cognome" class="block text-sm font-medium text-gray-700 mb-1">Cognome *</label>
                    <input type="text" name="cognome" id="cognome" value="{{ old('cognome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('cognome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="ragione_sociale" class="block text-sm font-medium text-gray-700 mb-1">Ragione Sociale (Opzionale)</label>
                <input type="text" name="ragione_sociale" id="ragione_sociale" value="{{ old('ragione_sociale') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('ragione_sociale') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900">
                    @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Telefono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Telefono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('telefono') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="richiesta" class="block text-sm font-medium text-gray-700 mb-1">Richiesta *</label>
                <textarea name="richiesta" id="richiesta" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('richiesta') }}</textarea>
                @error('richiesta') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="inline-flex justify-center px-6 pt-[17px] pb-[17px] btn">
                    Invia Richiesta
                </button>
            </div>
        </form>
    @endif
</div>
