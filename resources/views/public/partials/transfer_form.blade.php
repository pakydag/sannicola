@php
    $isEn = app()->getLocale() === 'en';
@endphp
<div class="bg-white shadow-sm xl:p-14 p-8 overflow-hidden relative rounded-lg" id="transfer">
    <div class="text-center mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $isEn ? 'Transfer Request' : 'Richiesta Transfer' }}</h3>
        <p class="text-gray-600">{{ $isEn ? 'Fill in the form below to request a transfer.' : 'Compila il modulo sottostante per richiedere un transfer.' }}</p>
    </div>

    @if(session('transfer_success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 mb-6 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-semibold text-lg">{{ session('transfer_success') }}</p>
            </div>
        </div>
    @else
        <form action="{{ route('public.transfer.submit') }}#transfer" method="POST" class="max-w-4xl mx-auto" x-data="{ roundTrip: false }">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'First Name *' : 'Nome *' }}</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Cognome -->
                <div>
                    <label for="cognome" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Last Name *' : 'Cognome *' }}</label>
                    <input type="text" name="cognome" id="cognome" value="{{ old('cognome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('cognome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'E-mail *' : 'E-mail *' }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Telefono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Phone *' : 'Telefono *' }}</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('telefono') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="my-8 border-gray-200">

            <div class="mb-6">
                <label for="luogo_arrivo" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Destination / Place of Arrival *' : 'Luogo di arrivo *' }}</label>
                <input type="text" name="luogo_arrivo" id="luogo_arrivo" value="{{ old('luogo_arrivo') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('luogo_arrivo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Data -->
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Date *' : 'Data *' }}</label>
                    <input type="date" name="data" id="data" value="{{ old('data') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('data') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Orario -->
                <div>
                    <label for="orario" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Time *' : 'Orario *' }}</label>
                    <input type="time" name="orario" id="orario" value="{{ old('orario') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('orario') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Numero di persone -->
                <div>
                    <label for="numero_persone" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Number of passengers *' : 'Numero di persone *' }}</label>
                    <input type="number" name="numero_persone" id="numero_persone" value="{{ old('numero_persone', 1) }}" min="1" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('numero_persone') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="andata_ritorno" value="1" x-model="roundTrip" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('andata_ritorno') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 font-medium">{{ $isEn ? 'Round Trip' : 'Andata e Ritorno' }}</span>
                </label>
            </div>

            <div x-show="roundTrip" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 rounded-md border border-gray-200" style="display: none;">
                <!-- Data Ritorno -->
                <div>
                    <label for="data_ritorno" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Return Date' : 'Data Ritorno' }}</label>
                    <input type="date" name="data_ritorno" id="data_ritorno" value="{{ old('data_ritorno') }}" :required="roundTrip" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('data_ritorno') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Orario Ritorno -->
                <div>
                    <label for="orario_ritorno" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Return Time' : 'Orario Ritorno' }}</label>
                    <input type="time" name="orario_ritorno" id="orario_ritorno" value="{{ old('orario_ritorno') }}" :required="roundTrip" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('orario_ritorno') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="messaggio" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Message / Special Requests (Optional)' : 'Messaggio / Richieste particolari (Opzionale)' }}</label>
                <textarea name="messaggio" id="messaggio" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('messaggio') }}</textarea>
                @error('messaggio') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6 flex items-start">
                <div class="flex items-center h-5">
                    <input id="privacy_transfer" name="privacy" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3">
                    <label for="privacy_transfer" class="text-sm text-gray-700">
                        @if($isEn)
                            Having read the <a href="/informative/informativa-form-contatti" target="_blank" class="text-indigo-600 hover:underline font-semibold">privacy policy</a>, I consent to the processing of my personal data for the contact form.
                        @else
                            Presa visione dell'<a href="/informative/informativa-form-contatti" target="_blank" class="text-indigo-600 hover:underline font-semibold">informativa sul form contatti</a>, acconsento al trattamento dei miei dati personali per il form contatti.
                        @endif
                    </label>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="inline-flex justify-center px-6 pt-[17px] pb-[17px] btn">
                    {{ $isEn ? 'Send Transfer Request' : 'Invia Richiesta Transfer' }}
                </button>
            </div>
        </form>
    @endif
</div>
