@php
    $isEn = app()->getLocale() === 'en';
@endphp
<div class="bg-white shadow-sm xl:p-14 p-8 overflow-hidden relative rounded-lg" id="car-rental">
    <div class="text-center mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $isEn ? 'Car Rental Request' : 'Richiesta Noleggio Auto' }}</h3>
        <p class="text-gray-600">{{ $isEn ? 'Fill in the form below to request a car rental.' : 'Compila il modulo sottostante per richiedere il noleggio di un\'auto.' }}</p>
    </div>

    @if(session('car_rental_success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 mb-6 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-semibold text-lg">{{ session('car_rental_success') }}</p>
            </div>
        </div>
    @else
        <form action="{{ route('public.car_rental.submit') }}#car-rental" method="POST" class="max-w-4xl mx-auto">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nome -->
                <div>
                    <label for="car_nome" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'First Name *' : 'Nome *' }}</label>
                    <input type="text" name="nome" id="car_nome" value="{{ old('nome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Cognome -->
                <div>
                    <label for="car_cognome" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Last Name *' : 'Cognome *' }}</label>
                    <input type="text" name="cognome" id="car_cognome" value="{{ old('cognome') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('cognome') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Email -->
                <div>
                    <label for="car_email" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'E-mail *' : 'E-mail *' }}</label>
                    <input type="email" name="email" id="car_email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Telefono -->
                <div>
                    <label for="car_telefono" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Phone *' : 'Telefono *' }}</label>
                    <input type="text" name="telefono" id="car_telefono" value="{{ old('telefono') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('telefono') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="my-8 border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Giorno e orario ritiro -->
                <div>
                    <label for="data_ritiro" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Pick Up Date & Time *' : 'Data e Orario Ritiro *' }}</label>
                    <div class="flex gap-2">
                        <input type="date" name="data_ritiro" id="data_ritiro" value="{{ old('data_ritiro') }}" required class="w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="time" name="orario_ritiro" value="{{ old('orario_ritiro') }}" required class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    @error('data_ritiro') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    @error('orario_ritiro') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Giorno e orario riconsegna -->
                <div>
                    <label for="data_riconsegna" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Drop Off Date & Time *' : 'Data e Orario Riconsegna *' }}</label>
                    <div class="flex gap-2">
                        <input type="date" name="data_riconsegna" id="data_riconsegna" value="{{ old('data_riconsegna') }}" required class="w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="time" name="orario_riconsegna" value="{{ old('orario_riconsegna') }}" required class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    @error('data_riconsegna') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    @error('orario_riconsegna') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6 w-full md:w-1/2">
                <label for="numero_posti" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Number of Seats *' : 'Numero Posti *' }}</label>
                <select name="numero_posti" id="numero_posti" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">{{ $isEn ? '-- Select --' : '-- Seleziona --' }}</option>
                    @for($i=1; $i<=9; $i++)
                        <option value="{{ $i }}" {{ old('numero_posti') == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i == 1 ? ($isEn ? 'seat' : 'posto') : ($isEn ? 'seats' : 'posti') }}
                        </option>
                    @endfor
                </select>
                @error('numero_posti') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="car_messaggio" class="block text-sm font-medium text-gray-700 mb-1">{{ $isEn ? 'Message / Special Requests (Optional)' : 'Messaggio / Richieste particolari (Opzionale)' }}</label>
                <textarea name="messaggio" id="car_messaggio" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('messaggio') }}</textarea>
                @error('messaggio') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6 flex items-start">
                <div class="flex items-center h-5">
                    <input id="privacy_car" name="privacy" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3">
                    <label for="privacy_car" class="text-sm text-gray-700">
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
                    {{ $isEn ? 'Send Car Rental Request' : 'Invia Richiesta Noleggio' }}
                </button>
            </div>
        </form>
    @endif
</div>
