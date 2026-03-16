@extends('public.layouts.main')

@section('title', 'Il Mio Account - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Area Clienti</h1>
            <p class="mt-2 text-sm text-gray-500">Benvenuto {{ $user->name }}, qui puoi gestire i tuoi ordini e i tuoi dati personali.</p>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-8">
            
            <!-- Sidebar Nav Area Clienti -->
            <aside class="lg:col-span-3 mb-8 lg:mb-0">
                <nav class="space-y-1 bg-white p-4 rounded-lg shadow">
                    <a href="{{ route('public.account.dashboard') }}" class="bg-indigo-50 text-indigo-700 hover:text-indigo-700 hover:bg-indigo-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-indigo-500 group-hover:text-indigo-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        I Miei Ordini
                    </a>
                    <a href="{{ route('public.account.profile') }}" class="text-gray-900 hover:text-gray-900 hover:bg-gray-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Dati Personali
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-200">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-900 hover:bg-red-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <svg class="text-red-400 group-hover:text-red-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Esci
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- Main Content: Storico Ordini -->
            <div class="lg:col-span-9">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Storico dei Tuoi Ordini</h3>
                    </div>
                    
                    @if($orders->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            Non hai ancora effettuato nessun ordine.
                            <div class="mt-4">
                                <a href="{{ route('public.shop.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Inizia lo shopping &rarr;</a>
                            </div>
                        </div>
                    @else
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <li class="p-6 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600 truncate">Ordine #{{ $order->numero_ordine }}</p>
                                            <p class="mt-1 text-sm text-gray-500">Del {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-gray-900">€ {{ number_format($order->totale_ordine, 2, ',', '.') }}</p>
                                            <p class="mt-1 text-xs font-semibold px-2 py-1 rounded inline-block 
                                                @if($order->stato === 'nuovo') bg-blue-100 text-blue-800 
                                                @elseif($order->stato === 'in_lavorazione') bg-yellow-100 text-yellow-800
                                                @elseif($order->stato === 'spedito') bg-green-100 text-green-800
                                                @elseif($order->stato === 'annullato') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ strtoupper($order->stato) }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 text-sm text-gray-600">
                                        <p class="font-medium text-gray-900">Spedito a:</p>
                                        <p>{{ $order->spedizione_nome }}</p>
                                        <p>{{ $order->spedizione_indirizzo }}, {{ $order->spedizione_cap }} {{ $order->spedizione_citta }} ({{ $order->spedizione_nazione }})</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
