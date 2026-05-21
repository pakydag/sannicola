@extends('public.layouts.main')

@section('content')
    @php
        $bannerImage = null;
        if (isset($structure)) {
            if ($structure->seo_image) {
                $bannerImage = asset($structure->seo_image);
            } elseif ($structure->photos->count() > 0) {
                $bannerImage = asset($structure->photos->first()->path);
            }
        }
        if (!$bannerImage) {
            $section = \App\Models\Section::where('modulo', 'booking')->first();
            $bannerImage = $section && $section->immagine ? asset($section->immagine) : null;
        }
    @endphp

    @if($bannerImage)
        <div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-bg-top bg-fixed px-6 lg:px-0" style="background-image: url('{{ $bannerImage }}');">
            <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0 relative z-10">
                <nav class="flex p-6 items-left text-sm font-medium text-gray-400">
                    <a href="{{ route('public.home') }}" class="hover:text-gray-900 transition">Home</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <a href="{{ route('public.booking.index') }}" class="hover:text-gray-900 transition">{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</a>
                    @if(isset($structure))
                        <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        <a href="{{ route('public.booking.show', $structure->id) }}" class="hover:text-gray-900 transition">{{ $structure->nome }}</a>
                    @endif
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Checkout' : 'Checkout' }}</span>
                </nav>
            </div>
        </div>
        <div class="bg-gray-50 pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
    @else
        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
    @endif

            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">{{ app()->getLocale() === 'en' ? 'Complete your Booking' : 'Completa la tua Prenotazione' }}</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recapito e Dati -->
                <div class="lg:col-span-2 space-y-6">
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('public.booking.process_checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold">{{ app()->getLocale() === 'en' ? 'Contact Information' : 'Informazioni di Contatto' }}</h2>
                                @if(!Auth::guard('booking_customer')->check())
                                    <button type="button" onclick="toggleLoginForm()" id="login-toggle-btn" class="text-indigo-600 text-sm font-bold hover:underline">
                                        {{ app()->getLocale() === 'en' ? 'Already registered? Login here' : 'Sei già registrato? Accedi qui' }}
                                    </button>
                                @endif
                            </div>

                            @if(!Auth::guard('booking_customer')->check())
                            <!-- Login Form (Hidden by default) -->
                            <div id="login-section" class="hidden mb-8 p-6 bg-indigo-50 rounded-xl border border-indigo-100">
                                <h3 class="text-sm font-bold text-indigo-900 mb-4">{{ app()->getLocale() === 'en' ? 'Access your Booking account' : 'Accedi al tuo account Booking' }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" id="login_email" class="w-full text-sm rounded-lg border-gray-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" id="login_password" class="w-full text-sm rounded-lg border-gray-200">
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <a href="{{ route('public.booking.forgot_password') }}" class="text-xs text-indigo-600 hover:underline">{{ app()->getLocale() === 'en' ? 'Forgot your password?' : 'Hai dimenticato la password?' }}</a>
                                    <button type="button" onclick="performAjaxLogin()" class="bg-indigo-600 text-white text-xs font-bold py-2 px-4 rounded-lg">
                                        {{ app()->getLocale() === 'en' ? 'Login' : 'Accedi' }}
                                    </button>
                                </div>
                                <div id="login-error" class="hidden mt-2 text-xs text-red-600 font-bold"></div>
                            </div>
                            @else
                                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-sm">{{ app()->getLocale() === 'en' ? 'You are logged in as' : 'Sei loggato come' }} <strong>{{ Auth::guard('booking_customer')->user()->nome }} {{ Auth::guard('booking_customer')->user()->cognome }}</strong></span>
                                </div>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $user = Auth::guard('booking_customer')->user();
                                @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'First Name *' : 'Nome *' }}</label>
                                    <input type="text" name="nome" value="{{ $user ? $user->nome : old('nome') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Last Name *' : 'Cognome *' }}</label>
                                    <input type="text" name="cognome" value="{{ $user ? $user->cognome : old('cognome') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" value="{{ $user ? $user->email : old('email') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" {{ $user ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Country *' : 'Nazione *' }}</label>
                                    <input type="text" name="nazione" value="{{ $user ? $user->nazione : old('nazione') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ app()->getLocale() === 'en' ? 'E.g. Italy' : 'E.g. Italia' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'City *' : 'Città *' }}</label>
                                    <input type="text" name="citta" value="{{ $user ? $user->citta : old('citta') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Phone Number *' : 'Telefono / Cellulare *' }}</label>
                                    <input type="text" name="telefono" value="{{ $user ? $user->telefono : old('telefono') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                @if(!Auth::guard('booking_customer')->check())
                                <div class="md:col-span-2 flex justify-between items-center bg-indigo-50 p-4 rounded-xl border border-indigo-100 mb-2 mt-4">
                                    <div>
                                        <p class="font-bold text-indigo-900 text-sm">{{ app()->getLocale() === 'en' ? 'Account Registration' : 'Registrazione Account' }}</p>
                                        <p class="text-xs text-indigo-700">{{ app()->getLocale() === 'en' ? 'You must enter a password to create your account.' : 'Dovrai inserire una password per creare il tuo account.' }}</p>
                                    </div>
                                    <button type="button" onclick="generateSecurePassword()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-3 rounded-lg shadow transition-all">
                                        ✨ {{ app()->getLocale() === 'en' ? 'Generate Secure Password' : 'Genera Password Sicura' }}
                                    </button>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                    <input type="password" name="password" id="password_field" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-[10px] text-gray-400 mt-1">{{ app()->getLocale() === 'en' ? 'Min. 8 characters, including numbers and symbols' : 'Min. 8 caratteri, inclusi numeri e simboli' }}</p>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Confirm Password *' : 'Conferma Password *' }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation_field" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2 flex items-center">
                                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="remember" class="ml-2 block text-sm text-gray-600">{{ app()->getLocale() === 'en' ? 'Remember password' : 'Ricorda password' }}</label>
                                </div>
                                @else
                                    {{-- Se loggato, inviamo una password finta o nulla, ma il controller dovrà gestire l'assenza di password per utenti esistenti --}}
                                    <input type="hidden" name="password" value="existing_customer">
                                    <input type="hidden" name="password_confirmation" value="existing_customer">
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mt-6">
                            <h2 class="text-xl font-bold mb-6">{{ app()->getLocale() === 'en' ? 'Payment Method' : 'Metodo di Pagamento' }}</h2>
                            <div class="space-y-4">
                                @php
                                    $stripe_enabled = \App\Models\Setting::where('key', 'booking_payment_stripe_enabled')->value('value') == '1';
                                    $paypal_enabled = \App\Models\Setting::where('key', 'booking_payment_paypal_enabled')->value('value') == '1';
                                    $bonifico_enabled = \App\Models\Setting::where('key', 'booking_payment_bonifico_enabled')->value('value') == '1';

                                    $first_active = null;
                                    if($stripe_enabled) $first_active = 'stripe';
                                    elseif($paypal_enabled) $first_active = 'paypal';
                                    elseif($bonifico_enabled) $first_active = 'bonifico';
                                @endphp

                                @if($stripe_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="stripe" {{ $first_active == 'stripe' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">{{ app()->getLocale() === 'en' ? 'Credit Card (Stripe)' : 'Carta di Credito (Stripe)' }}</span>
                                            <span class="block text-sm text-gray-500">{{ app()->getLocale() === 'en' ? 'Secure and instant payment' : 'Pagamento sicuro e immediato' }}</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" class="h-6 w-auto opacity-60 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all">
                                    </label>
                                @endif

                                @if($paypal_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="paypal" {{ $first_active == 'paypal' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">PayPal</span>
                                            <span class="block text-sm text-gray-500">{{ app()->getLocale() === 'en' ? 'Pay with your PayPal account' : 'Paga con il tuo account PayPal' }}</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-6 w-auto opacity-60 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all">
                                    </label>
                                @endif

                                @if($bonifico_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="bonifico" {{ $first_active == 'bonifico' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">{{ app()->getLocale() === 'en' ? 'Bank Transfer' : 'Bonifico Bancario' }}</span>
                                            <span class="block text-sm text-gray-500">{{ app()->getLocale() === 'en' ? 'You will receive the bank transfer details via email' : 'Riceverai i dati per il bonifico via email' }}</span>
                                        </div>
                                        <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </label>
                                @endif

                                @if(!$stripe_enabled && !$paypal_enabled && !$bonifico_enabled)
                                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm italic">
                                        ⚠️ {{ app()->getLocale() === 'en' ? 'No payment methods enabled for Booking. Contact the administrator.' : 'Nessun metodo di pagamento abilitato per il Booking. Contatta l\'amministratore.' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 px-8 rounded-2xl shadow-xl shadow-indigo-200 transition-all transform active:scale-[0.98]">
                            {{ app()->getLocale() === 'en' ? 'Confirm and Pay' : 'Conferma e Paga' }} €{{ number_format($pending['totale_prezzo'], 2, ',', '.') }}
                        </button>
                    </form>
                </div>

                <!-- Riepilogo Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                        <div class="h-32 w-full bg-indigo-600 p-6 flex flex-col justify-end">
                            <h3 class="text-white font-bold text-lg">{{ app()->getLocale() === 'en' ? 'Stay Summary' : 'Riepilogo Soggiorno' }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-start gap-4">
                                @if($structure->photos->count() > 0)
                                    <img src="{{ asset($structure->photos->first()->path) }}" class="h-16 w-16 rounded-lg object-cover">
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900">{{ $structure->nome }}</p>
                                    <p class="text-xs text-gray-500">{{ $structure->camere_letto }} {{ app()->getLocale() === 'en' ? 'Bedrooms' : 'Camere' }} / {{ $structure->bagni }} {{ app()->getLocale() === 'en' ? 'Bathrooms' : 'Bagni' }}</p>
                                </div>
                            </div>

                            <div class="border-t pt-6 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">{{ app()->getLocale() === 'en' ? 'Arrival' : 'Arrivo' }}</span>
                                    <span class="font-bold">{{ \Carbon\Carbon::parse($pending['start_date'])->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">{{ app()->getLocale() === 'en' ? 'Departure' : 'Partenza' }}</span>
                                    <span class="font-bold">{{ \Carbon\Carbon::parse($pending['end_date'])->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">{{ app()->getLocale() === 'en' ? 'Guests' : 'Ospiti' }}</span>
                                    <span class="font-bold">
                                        @if(!empty($pending['ospiti_dettaglio']))
                                            @php
                                                $ospitiParts = [];
                                                $totalOspiti = 0;
                                                foreach ($pending['ospiti_dettaglio'] as $variantId => $count) {
                                                    if ($count > 0) {
                                                        $variant = $structure->variants->find($variantId);
                                                        $name = $variant ? $variant->nome : (app()->getLocale() === 'en' ? 'Guest' : 'Ospite');
                                                        $ospitiParts[] = $count . ' ' . $name;
                                                        $totalOspiti += $count;
                                                    }
                                                }
                                            @endphp
                                            {{ $totalOspiti }} ({{ implode(', ', $ospitiParts) }})
                                        @else
                                            {{ app()->getLocale() === 'en' ? 'Fixed price' : 'Prezzo fisso' }}
                                        @endif
                                    </span>
                                </div>
                                @if(!empty($pending['extra_dettaglio']))
                                    @php
                                        $selectedExtras = \App\Models\BookingExtra::whereIn('id', $pending['extra_dettaglio'])->get();
                                    @endphp
                                    <div class="flex justify-between text-sm py-1">
                                        <span class="text-gray-500 font-bold uppercase text-[10px]">{{ app()->getLocale() === 'en' ? 'Extras' : 'Extra' }}</span>
                                        <span class="font-bold text-right text-xs">
                                            {{ $selectedExtras->pluck('nome')->implode(', ') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="border-t pt-6">
                                <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                    <span class="font-bold text-indigo-700">{{ app()->getLocale() === 'en' ? 'Total to Pay' : 'Totale da Pagare' }}</span>
                                    <span class="font-extrabold text-2xl text-indigo-700">€{{ number_format($pending['totale_prezzo'], 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleLoginForm() {
            const section = document.getElementById('login-section');
            const btn = document.getElementById('login-toggle-btn');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                btn.innerText = '{{ app()->getLocale() === "en" ? "Cancel login" : "Annulla accesso" }}';
            } else {
                section.classList.add('hidden');
                btn.innerText = '{{ app()->getLocale() === "en" ? "Already registered? Login here" : "Sei già registrato? Accedi qui" }}';
            }
        }

        async function performAjaxLogin() {
            const email = document.getElementById('login_email').value;
            const password = document.getElementById('login_password').value;
            const remember = document.getElementById('remember') ? document.getElementById('remember').checked : false;
            const errorDiv = document.getElementById('login-error');

            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route('public.booking.login') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, password, remember })
                });

                const data = await response.json();

                if (data.success) {
                    location.reload(); // Ricarichiamo per applicare lo stato di auth
                } else {
                    errorDiv.innerText = data.message || '{{ app()->getLocale() === "en" ? "Error during login." : "Errore durante l\'accesso." }}';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.innerText = '{{ app()->getLocale() === "en" ? "Connection error." : "Errore di connessione." }}';
                errorDiv.classList.remove('hidden');
            }
        }

        function generateSecurePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
            let retVal = "";
            for (let i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }

            // Ensure mixed case, symbols and numbers logic (rough check)
            const passwordField = document.getElementById('password_field');
            const confirmationField = document.getElementById('password_confirmation_field');

            if (passwordField && confirmationField) {
                passwordField.type = 'text'; // Show it temporarily
                passwordField.value = retVal;
                confirmationField.type = 'text';
                confirmationField.value = retVal;

                alert('{{ app()->getLocale() === "en" ? "Password generated successfully: " : "Password generata con successo: " }}' + retVal + '\n\n{{ app()->getLocale() === "en" ? "Make sure to keep it in a safe place." : "Assicurati di conservarla in un luogo sicuro." }}');
            }
        }
    </script>
    @endpush
@endsection
