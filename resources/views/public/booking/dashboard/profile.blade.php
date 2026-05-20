@extends('public.layouts.main')

@section('content')
    @if(isset($section) && $section->immagine)
    <div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-center bg-fixed px-6 lg:px-0" style="background-image: url('{{ asset($section->immagine) }}');">
        <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0">
            <nav class=" flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
                <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
                <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                <span>Area Clienti</span>
            </nav>
        </div>
    </div>
    <div class="bg-gray-50 min-h-screen pb-12 lg:mx-auto">
    @else
    <div class="bg-gray-50 min-h-screen py-12">
    @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 {{ isset($section) && $section->immagine ? 'pt-8' : '' }}">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-64 space-y-2">
                <a href="{{ route('public.booking.dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white text-gray-700 hover:bg-gray-100 rounded-xl font-bold transition-all border border-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Le mie Prenotazioni
                </a>
                <a href="{{ route('public.booking.dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Il mio Profilo
                </a>
                <form action="{{ route('public.booking.dashboard.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 bg-white text-red-600 hover:bg-red-50 rounded-xl font-bold transition-all border border-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Esci
                    </button>
                </form>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <h1 class="text-2xl font-bold text-gray-900">Il mio Profilo</h1>
                        <p class="text-gray-500 text-sm">Gestisci i tuoi dati personali e la sicurezza dell'account.</p>
                    </div>

                    @if(session('success'))
                        <div class="mx-8 mt-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 font-bold text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('public.booking.dashboard.profile.update') }}" method="POST" class="p-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nome</label>
                                <input type="text" name="nome" value="{{ $customer->nome }}" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Cognome</label>
                                <input type="text" name="cognome" value="{{ $customer->cognome }}" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">E-mail (non modificabile)</label>
                                <input type="email" value="{{ $customer->email }}" disabled class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-400">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Telefono</label>
                                <input type="text" name="telefono" value="{{ $customer->telefono }}" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nazione</label>
                                <input type="text" name="nazione" value="{{ $customer->nazione }}" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Città</label>
                                <input type="text" name="citta" value="{{ $customer->citta }}" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="bg-gray-50 -mx-8 p-8 border-y border-gray-100 mb-8">
                            <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Modifica Password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nuova Password</label>
                                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Conferma Nuova Password</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-4 italic">Lascia vuoto se non desideri cambiare la password.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95">
                                Salva Modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
