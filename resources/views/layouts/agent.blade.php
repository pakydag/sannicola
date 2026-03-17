<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portale Agente - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar Agente -->
        <aside class="w-full md:w-64 bg-white text-gray-900 flex-shrink-0 shadow-sm z-20 border-r border-gray-200">
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-2xl font-bold tracking-tight uppercase text-indigo-900">B2B Portal</h1>
                <p class="text-xs text-gray-400 font-medium uppercase mt-1 tracking-widest">Area Agenti</p>
            </div>
            
            <nav class="p-4 space-y-1">
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.dashboard') ? 'bg-indigo-600 text-white font-black shadow-lg' : 'hover:bg-gray-100 text-gray-900 font-bold' }}">
                    <span class="mr-3 text-lg">📊</span> <span class="text-sm uppercase tracking-wide">Dashboard</span>
                </a>
                <a href="{{ route('agent.catalog') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.catalog') || request()->routeIs('agent.product') ? 'bg-indigo-600 text-white font-black shadow-lg' : 'hover:bg-gray-100 text-gray-900 font-bold' }}">
                    <span class="mr-3 text-lg">📦</span> <span class="text-sm uppercase tracking-wide">Catalogo Prodotti</span>
                </a>
                <a href="{{ route('agent.cart') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.cart') ? 'bg-indigo-600 text-white font-black shadow-lg' : 'hover:bg-gray-100 text-gray-900 font-bold' }}">
                    <span class="mr-3 text-lg">🛒</span> <span class="text-sm uppercase tracking-wide">Carrello</span>
                    @if(count(session('b2b_cart', [])) > 0)
                        <span class="ml-auto bg-rose-600 text-white text-[10px] px-2 py-0.5 rounded-full font-black shadow-sm">{{ count(session('b2b_cart')) }}</span>
                    @endif
                </a>
                <a href="{{ route('agent.orders') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.orders') || request()->routeIs('agent.order_detail') ? 'bg-indigo-600 text-white font-black shadow-lg' : 'hover:bg-gray-100 text-gray-900 font-bold' }}">
                    <span class="mr-3 text-lg">📝</span> <span class="text-sm uppercase tracking-wide">Ordini Inviati</span>
                </a>
                
                <div class="pt-10">
                    <a href="{{ route('agent.profile') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.profile') ? 'bg-indigo-600 text-white font-black shadow-lg' : 'hover:bg-gray-100 text-gray-900 font-bold' }}">
                        <span class="mr-3 text-lg">👤</span> <span class="text-sm uppercase tracking-wide">Il Mio Profilo</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-3 rounded-lg text-rose-600 hover:bg-rose-50 font-bold transition">
                            <span class="mr-3 text-lg">🚪</span> <span class="text-sm uppercase tracking-wide">Esci</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Mobile Header -->
            <header class="md:hidden bg-indigo-900 text-white p-4 flex justify-between items-center shadow-lg">
                <span class="font-black">B2B PORTAL</span>
                <button class="p-2 border border-indigo-700 rounded text-indigo-100">Menu</button>
            </header>

            <!-- Desktop Sub-header -->
            @isset($header)
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-6 py-4">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="p-6 md:p-10 flex-1">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
            
            <footer class="bg-white border-t border-gray-200 p-6 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }} B2B Portal. Tutti i diritti riservati.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
