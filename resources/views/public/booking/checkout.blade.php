@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Completa la tua Prenotazione</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recapito e Dati -->
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('public.booking.process_checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <h2 class="text-xl font-bold mb-6">Informazioni di Contatto</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                                    <input type="text" name="nome" value="{{ Auth::user() ? explode(' ', Auth::user()->name)[0] : old('nome') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cognome *</label>
                                    <input type="text" name="cognome" value="{{ Auth::user() ? (explode(' ', Auth::user()->name)[1] ?? '') : old('cognome') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" value="{{ Auth::user() ? Auth::user()->email : old('email') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefono / Cellulare *</label>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mt-6">
                            <h2 class="text-xl font-bold mb-6">Metodo di Pagamento</h2>
                            <div class="space-y-4">
                                @php
                                    $stripe_enabled = \App\Models\Setting::where('key', 'stripe_enabled')->value('value') == '1';
                                    $paypal_enabled = \App\Models\Setting::where('key', 'paypal_enabled')->value('value') == '1';
                                    $bonifico_enabled = \App\Models\Setting::where('key', 'bonifico_enabled')->value('value') == '1';
                                    $first_active = null;
                                    if($stripe_enabled) $first_active = 'stripe';
                                    elseif($paypal_enabled) $first_active = 'paypal';
                                    elseif($bonifico_enabled) $first_active = 'bonifico';
                                @endphp

                                @if($stripe_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="stripe" {{ $first_active == 'stripe' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">Carta di Credito (Stripe)</span>
                                            <span class="block text-sm text-gray-500">Pagamento sicuro e immediato</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" class="h-6 w-auto opacity-60 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all">
                                    </label>
                                @endif

                                @if($paypal_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="paypal" {{ $first_active == 'paypal' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">PayPal</span>
                                            <span class="block text-sm text-gray-500">Paga con il tuo account PayPal</span>
                                        </div>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-6 w-auto opacity-60 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all">
                                    </label>
                                @endif

                                @if($bonifico_enabled)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-indigo-50 transition-colors border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                                        <input type="radio" name="payment_method" value="bonifico" {{ $first_active == 'bonifico' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <div class="ml-4 flex-1">
                                            <span class="block font-bold text-gray-900">Bonifico Bancario</span>
                                            <span class="block text-sm text-gray-500">Riceverai i dati per il bonifico via email</span>
                                        </div>
                                        <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 px-8 rounded-2xl shadow-xl shadow-indigo-200 transition-all transform active:scale-[0.98]">
                            Conferma e Paga €{{ number_format($pending['totale_prezzo'], 2, ',', '.') }}
                        </button>
                    </form>
                </div>

                <!-- Riepilogo Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                        <div class="h-32 w-full bg-indigo-600 p-6 flex flex-col justify-end">
                            <h3 class="text-white font-bold text-lg">Riepilogo Soggiorno</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-start gap-4">
                                @if($structure->photos->count() > 0)
                                    <img src="{{ asset($structure->photos->first()->path) }}" class="h-16 w-16 rounded-lg object-cover">
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900">{{ $structure->nome }}</p>
                                    <p class="text-xs text-gray-500">{{ $structure->camere_letto }} Camere / {{ $structure->bagni }} Bagni</p>
                                </div>
                            </div>

                            <div class="border-t pt-6 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">Arrivo</span>
                                    <span class="font-bold">{{ \Carbon\Carbon::parse($pending['start_date'])->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">Partenza</span>
                                    <span class="font-bold">{{ \Carbon\Carbon::parse($pending['end_date'])->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 font-bold uppercase text-[10px]">Ospiti</span>
                                    <span class="font-bold">{{ $pending['adulti'] + $pending['bambini'] }} ({{ $pending['adulti'] }} ad. @if($pending['bambini'] > 0), {{ $pending['bambini'] }} bam. @endif)</span>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                    <span class="font-bold text-indigo-700">Totale da Pagare</span>
                                    <span class="font-extrabold text-2xl text-indigo-700">€{{ number_format($pending['totale_prezzo'], 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
