@extends('public.layouts.main')

@section('content')
    @php
        $section = \App\Models\Section::where('modulo', 'booking')->first();
        $bannerImage = $section && $section->immagine ? asset($section->immagine) : null;
    @endphp

    @if($bannerImage)
        <div class="gradient relative bg-gray-50 h-64 flex items-end bg-cover bg-center px-6 lg:px-0" style="background-image: url('{{ $bannerImage }}');">
            <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0 relative z-10">
                <nav class="flex p-6 items-left text-sm font-medium text-gray-400">
                    <a href="{{ route('public.home') }}" class="hover:text-gray-900 transition">Home</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <a href="{{ route('public.booking.index') }}" class="hover:text-gray-900 transition">{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Booking Area' : 'Area Booking' }}</span>
                </nav>
            </div>
        </div>
        <div class="bg-gray-50 pb-20">
            <div class="max-w-md w-full mx-auto px-4 pt-10">
    @else
        <div class="bg-gray-50 min-h-screen py-24 flex items-center justify-center">
            <div class="max-w-md w-full mx-auto px-4">
    @endif
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Header decorato -->
            <div class="bg-indigo-600 px-8 py-10 text-center text-white relative">
                <div class="absolute inset-0 opacity-10 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80');"></div>
                <h1 class="text-3xl font-extrabold relative z-10">{{ app()->getLocale() === 'en' ? 'Booking Area' : 'Area Booking' }}</h1>
                <p class="text-indigo-100 text-sm mt-2 relative z-10">{{ app()->getLocale() === 'en' ? 'Login to manage your bookings and profile' : 'Accedi per gestire le tue prenotazioni e il tuo profilo' }}</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form id="customer-login-form" onsubmit="performLogin(event)">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Email Address' : 'Indirizzo Email' }}</label>
                            <input type="email" id="email" name="email" autocomplete="username" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3" placeholder="latua@email.com">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <a href="{{ route('public.booking.forgot_password') }}" class="text-xs text-indigo-600 hover:underline">{{ app()->getLocale() === 'en' ? 'Forgot password?' : 'Password dimenticata?' }}</a>
                            </div>
                            <input type="password" id="password" name="password" autocomplete="current-password" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3" placeholder="••••••••">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4">
                            <label for="remember" class="ml-2 block text-sm text-gray-600">{{ app()->getLocale() === 'en' ? 'Remember password' : 'Ricorda password' }}</label>
                        </div>

                        <div id="login-error-msg" class="hidden p-4 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-100"></div>

                        <button type="submit" id="submit-btn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg shadow-indigo-100 transition-all flex justify-center items-center gap-2">
                            <span>{{ app()->getLocale() === 'en' ? 'Login to Booking Area' : "Accedi all'Area Booking" }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">{{ app()->getLocale() === 'en' ? "Don't have a booking yet?" : 'Non hai ancora una prenotazione?' }}</p>
                    <a href="{{ route('public.booking.index') }}" class="mt-2 inline-block text-sm text-indigo-600 font-bold hover:underline">{{ app()->getLocale() === 'en' ? 'Search and book a property &rarr;' : 'Cerca e prenota una struttura &rarr;' }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function performLogin(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;
        const errorDiv = document.getElementById('login-error-msg');
        const submitBtn = document.getElementById('submit-btn');

        errorDiv.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '{{ app()->getLocale() === "en" ? "Logging in..." : "Accesso in corso..." }}';

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
                // Redirect directly to dashboard
                window.location.href = '{{ route('public.booking.dashboard.index') }}';
            } else {
                errorDiv.innerText = data.message || '{{ app()->getLocale() === "en" ? "Invalid credentials." : "Credenziali non valide." }}';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>{{ app()->getLocale() === "en" ? "Login to Booking Area" : "Accedi all\'Area Booking" }}</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
            }
        } catch (error) {
            errorDiv.innerText = '{{ app()->getLocale() === "en" ? "Server connection error." : "Errore di connessione al server." }}';
            errorDiv.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>{{ app()->getLocale() === "en" ? "Login to Booking Area" : "Accedi all\'Area Booking" }}</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
        }
    }
</script>
@endpush
@endsection
