@extends('public.layouts.main')

@section('content')
    @php
        $section = \App\Models\Section::where('modulo', 'booking')->first();
        $bannerImage = $section && $section->immagine ? asset($section->immagine) : null;
    @endphp

    @if($bannerImage)
        <div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-center bg-fixed px-6 lg:px-0" style="background-image: url('{{ $bannerImage }}');">
            <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0">
                <nav class="flex p-6 items-left text-sm font-medium text-gray-400">
                    <a href="{{ route('public.home') }}" class="hover:text-gray-900 transition">Home</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <a href="{{ route('public.booking.index') }}" class="hover:text-gray-900 transition">{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Password Recovery' : 'Recupero Password' }}</span>
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
                <h1 class="text-3xl font-extrabold relative z-10">{{ app()->getLocale() === 'en' ? 'Password Recovery' : 'Recupero Password' }}</h1>
                <p class="text-indigo-100 text-sm mt-2 relative z-10">{{ app()->getLocale() === 'en' ? 'Enter your email to receive a temporary password' : 'Inserisci la tua email per ricevere una password temporanea' }}</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-bold rounded-xl border border-green-100">
                        {{ session('success') }}
                    </div>
                    <div class="text-center">
                        <a href="{{ route('public.booking.login.view') }}" class="w-full inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            {{ app()->getLocale() === 'en' ? 'Back to Login' : 'Torna al Login' }}
                        </a>
                    </div>
                @else
                    <form action="{{ route('public.booking.forgot_password.submit') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ app()->getLocale() === 'en' ? 'Email Address' : 'Indirizzo Email' }}</label>
                                <input type="email" id="email" name="email" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3" placeholder="latua@email.com" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(session('error'))
                                <div class="p-4 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-100">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg shadow-indigo-100 transition-all flex justify-center items-center gap-2">
                                <span>{{ app()->getLocale() === 'en' ? 'Send New Password' : 'Invia Nuova Password' }}</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <a href="{{ route('public.booking.login.view') }}" class="text-sm text-indigo-600 font-bold hover:underline">{{ app()->getLocale() === 'en' ? '← Back to login page' : '← Torna alla pagina di login' }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
