<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('public.home') }}" class="text-2xl font-bold text-indigo-600 flex items-center">
                        @php
                            $siteLogo = \App\Models\Setting::where('key', 'site_logo')->value('value');
                        @endphp
                        @if($siteLogo)
                            <img src="{{ asset($siteLogo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto object-contain">
                        @else
                            {{ config('app.name', 'Il Mio Sito') }}
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('public.home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('public.home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        Home
                    </a>
                    
                    @php
                        $shopEnabled = \App\Models\Setting::where('key', 'shop_enabled')->value('value');
                    @endphp
                    @if($shopEnabled == '1')
                        <a href="{{ route('public.shop.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('public.shop.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                            Shop
                        </a>
                    @endif

                    @php
                        $bookingEnabled = \App\Models\Setting::where('key', 'booking_enabled')->value('value');
                    @endphp
                    @if($bookingEnabled == '1')
                        <a href="{{ route('public.booking.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('public.booking.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                            Booking
                        </a>
                    @endif
                    
                    @if(isset($shared_sezioni))
                        @foreach($shared_sezioni as $sez)
                            @if($sez->tipo == 'archivio' && $sez->menu_a_tendina)
                                <!-- Dropdown per Archivio -->
                                <div class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent" x-data="{ open: false }" @click.away="open = false" @mouseenter="open = true" @mouseleave="open = false">
                                    <a href="{{ route('public.sezione', $sez->slug ?? $sez->id.'-it') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium leading-5 transition duration-150 ease-in-out pb-5">
                                        {{ $sez->nome }}
                                        <svg class="ml-1 h-4 w-4 inline-block transform transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100" 
                                         x-transition:enter-start="transform opacity-0 scale-95" 
                                         x-transition:enter-end="transform opacity-100 scale-100" 
                                         x-transition:leave="transition ease-in duration-75" 
                                         x-transition:leave-start="transform opacity-100 scale-100" 
                                         x-transition:leave-end="transform opacity-0 scale-95" 
                                         class="origin-top-left absolute left-0 top-12 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 divide-y divide-gray-100 focus:outline-none" style="display: none;">
                                        
                                        <div class="py-1">
                                            <!-- Link a "Vai all'archivio principale" -->
                                            <a href="{{ route('public.sezione', $sez->slug ?? $sez->id.'-it') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 font-semibold border-b">
                                                Tutti gli articoli
                                            </a>
                                            <!-- Singoli Articoli -->
                                            @foreach($sez->articles()->where('visibile', true)->latest()->get() as $articolo)
                                                <a href="{{ route('public.articolo', ['sezione_slug' => $sez->slug ?? $sez->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-2 border-transparent hover:border-indigo-500">
                                                    {{ Str::limit($articolo->titolo, 30) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Link Semplice -->
                                <a href="{{ route('public.sezione', $sez->slug ?? $sez->id.'-it') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is($sez->slug ?? $sez->id.'-it') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    {{ $sez->nome }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                </nav>
            </div>
            
            <!-- Icone destra (Account + Carrello) -->
            <div class="flex items-center space-x-2 sm:ml-6">
                @if($bookingEnabled == '1')
                    @if(Auth::guard('booking_customer')->check())
                        <!-- Icona Booking Customer -->
                        <div class="relative inline-flex items-center" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1" title="Area Booking">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div x-show="open" class="absolute right-0 top-12 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 py-1" style="display: none;">
                                <div class="px-4 py-2 text-xs text-gray-400 border-b">Area Booking</div>
                                <a href="{{ route('public.booking.dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 italic">Le mie prenotazioni</a>
                                <a href="{{ route('public.booking.dashboard.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 italic">Profilo</a>
                                <form action="{{ route('public.booking.dashboard.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 italic">Esci</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Icona Login Ospite Booking -->
                        <a href="{{ route('public.booking.dashboard.index') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-indigo-600 transition-colors" title="Accedi Area Booking">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </a>
                    @endif
                @endif

                @if($shopEnabled == '1' || Auth::check())
                    @auth
                        <!-- Icona Account Autenticato -->
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="relative inline-flex items-center p-2 text-indigo-600 hover:text-indigo-800 transition-colors" title="Dashboard Admin">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </a>
                        @elseif($shopEnabled == '1')
                            <a href="{{ route('public.account.dashboard') }}" class="relative inline-flex items-center p-2 text-indigo-600 hover:text-indigo-800 transition-colors" title="Il Mio Account Shop">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </a>
                        @endif
                    @else
                        <!-- Icona Login Ospite Shop -->
                        <a href="{{ route('login') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-indigo-600 transition-colors" title="Accedi Shop / Registrati">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    @endauth
                @endif

                @if($shopEnabled == '1')
                <!-- Icona Carrello -->
                <a href="{{ route('public.shop.cart.index') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @php
                        $cartCount = array_sum(array_column(session()->get('cart', []), 'quantita'));
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                @endif
            </div>
        </div>
    </div>
</header>
