@php
    $siteLogo = \App\Models\Setting::where('key', 'site_logo')->value('value');
    $shopEnabled = \App\Models\Setting::where('key', 'shop_enabled')->value('value');
    $bookingEnabled = \App\Models\Setting::where('key', 'booking_enabled')->value('value');
    $englishEnabled = \App\Models\Setting::where('key', 'english_enabled')->value('value') == '1';
    $currentLocale = session('locale', 'it');
@endphp

<style>
/* Rende la transizione fluida come il resto del menu */
a[title="Accedi Area Booking"],
a[title="Accedi Area Booking"] svg {
    transition: all 0.3s ease-in-out !important;
}
</style>

<header x-data="{ scrolled: false, mobileMenuOpen: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })" class="menu top-0 left-0 w-full z-50 transition-all duration-300 border-b" :class="scrolled ? 'fixed bg-white shadow-md border-gray-200' : 'absolute bg-gradient-to-b from-black/80 to-transparent border-white/100 shadow-none'">
    <div class="max-w-7xl mx-auto w-full">
        <!-- Top bar with language selector and booking personal area, aligned to the right, no border -->
        @if($englishEnabled || $bookingEnabled == '1')
            <div class="flex items-center justify-end py-2 pb-0 px-4 lg:px-0 gap-4 transition-all duration-300">
                @if($englishEnabled)
                    <div class="flex items-center space-x-3 mr-2 bg-black/10 px-2 py-1 rounded-full border border-white/10" :class="scrolled ? 'bg-gray-100 border-gray-200 text-black' : 'bg-black/20 border-white/20 text-white'">
                        <a href="{{ route('set-locale', 'it') }}" class="flex items-center justify-center transition-transform hover:scale-115 {{ $currentLocale === 'it' ? 'scale-110 drop-shadow-md' : 'opacity-50 hover:opacity-100' }}" title="Italiano">
                            <span class="text-lg">🇮🇹</span>
                        </a>
                        <a href="{{ route('set-locale', 'en') }}" class="flex items-center justify-center transition-transform hover:scale-115 {{ $currentLocale === 'en' ? 'scale-110 drop-shadow-md' : 'opacity-50 hover:opacity-100' }}" title="English">
                            <span class="text-lg">🇬🇧</span>
                        </a>
                    </div>
                @endif

                @if($bookingEnabled == '1')
                    @if(Auth::guard('booking_customer')->check())
                        <div class="relative inline-flex items-center" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" :class="scrolled ? 'text-gray-600 hover:text-primary' : 'text-white/80 hover:text-white'" class="p-2 transition-colors flex items-center gap-1" title="Area Booking">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                <svg class="h-4 w-4 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-12 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 py-1" style="display: none;">
                                <div class="px-4 py-2 text-xs text-gray-400 border-b">Area Booking</div>
                                <a href="{{ route('public.booking.dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 italic">Le mie prenotazioni</a>
                                <a href="{{ route('public.booking.dashboard.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 italic">Profilo</a>
                                <form action="{{ route('public.booking.dashboard.logout') }}" method="POST">@csrf<button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 italic">Esci</button></form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('public.booking.dashboard.index') }}" :class="scrolled ? 'text-gray-600 hover:text-primary' : 'text-white/80 hover:text-white'" class="relative inline-flex items-center p-2 transition-colors" title="Accedi Area Booking">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </a>
                    @endif
                @endif
            </div>
        @endif
        <div class="flex items-center justify-between h-20">
             <!-- Logo -->
                <div class="flex shrink-0 mr-5 ml-4 lg:ml-0">
                    <a href="{{ route('public.home') }}" class="text-2xl font-bold text-indigo-600 flex items-center">
                        @if($siteLogo)
                            <img src="{{ asset($siteLogo) }}" alt="{{ config('app.name') }}" :class="scrolled ? '' : 'brightness-0 invert'" class="h-14 w-auto transition-all duration-300">
                        @else
                            {{ config('app.name', 'Il Mio Sito') }}
                        @endif
                    </a>
                </div>
            <div class="flex items-center gap-7 ml-auto space-x-4">
                <!-- Mobile menu button -->
                <div class="-ml-2 mr-2 flex items-center lg:hidden">
                  <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
        :class="scrolled ? 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' : 'text-white hover:text-white/80 hover:bg-white/10'"
        class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out"
        aria-label="Menu" :aria-expanded="mobileMenuOpen">
    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>
                </div>



                <!-- Navigation Links -->
                <nav class="menu tracking-tight hidden gap-8 sm:-my-px lg:flex justify-center items-center">


                    @if(isset($shared_sezioni))
                        @foreach($shared_sezioni->where('mostra_nel_menu', true) as $sez)
                            @if(
                                ($sez->modulo === 'shop' && $shopEnabled != '1') ||
                                ($sez->modulo === 'b2b' && $shopEnabled != '1') ||
                                ($sez->modulo === 'booking' && $bookingEnabled != '1')
                            )
                                @continue
                            @endif
                            @php
                                $url = route('public.sezione', $sez->slug ?? $sez->id.'-it');
                                if ($sez->modulo === 'home') $url = route('public.home');
                                if ($sez->modulo === 'shop') $url = route('public.shop.index');
                                if ($sez->modulo === 'booking') $url = route('public.booking.index');
                                if ($sez->modulo === 'b2b') $url = route('agent.dashboard');

                                $isActive = request()->is($sez->slug ?? $sez->id.'-it') ||
                                            ($sez->modulo === 'home' && request()->routeIs('public.home')) ||
                                            ($sez->modulo === 'shop' && request()->routeIs('public.shop.*')) ||
                                            ($sez->modulo === 'booking' && request()->routeIs('public.booking.*')) ||
                                            ($sez->modulo === 'b2b' && request()->routeIs('agent.*'));
                            @endphp

                            @if($sez->tipo == 'archivio' && $sez->menu_a_tendina && !$sez->modulo)
                                <!-- Dropdown per Archivio (Solo per sezioni CMS normali) -->
                                <div class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent" x-data="{ open: false }" @click.away="open = false" @mouseenter="open = true" @mouseleave="open = false">
                                    <a href="{{ $url }}" :class="scrolled ? 'text-gray-500 hover:text-black' : 'text-white/80 hover:text-white'" class="text-base leading-5 transition duration-150 ease-in-out inline-block">{{ $sez->nome }} <svg class="ml-1 h-4 w-4 inline-block transform transition-transform duration-200" :class="{'rotate-180': open, 'text-gray-400': scrolled, 'text-white/50': !scrolled}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></a>

                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-left absolute left-0 top-12 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 divide-y divide-gray-100 focus:outline-none" style="display: none;">

                                        <div class="py-1">
                                            <a href="{{ $url }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 font-semibold border-b">
                                                Tutti gli articoli
                                            </a>
                                            @foreach($sez->articles()->where('visibile', true)->latest()->get() as $articolo)
                                                <a href="{{ route('public.articolo', ['sezione_slug' => $sez->slug ?? $sez->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-l-2 border-transparent hover:border-indigo-500">
                                                    {{ Str::limit($articolo->titolo, 30) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Link Semplice (Module o Pagina) -->
                                <a href="{{ $url }}" :class="scrolled ? '{{ $isActive ? 'border-primary text-primary font-semibold' : 'border-transparent hover:text-primary hover:border-primary hover:font-semibold' }}' : '{{ $isActive ? 'border-white text-white font-semibold' : 'border-transparent text-white hover:text-white hover:border-white hover:font-semibold font-medium' }}'" class="inline-flex items-center pb-1 border-b-2 font-medium leading-5 transition duration-150 ease-in-out">{{ $sez->nome }}</a>
                            @endif
                        @endforeach
                    @endif
                </nav>
            </div>

            <div class="flex items-center space-x-5 sm:ml-6 hidden">
    @if($shopEnabled == '1' || Auth::check())
        @auth
            <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('public.account.dashboard') }}" :class="scrolled ? 'text-gray-600 hover:text-black' : 'text-white/80 hover:text-white'" class="relative inline-flex items-center p-2 transition-colors" title="Il Mio Account">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </a>
        @else
            <a href="{{ route('login') }}" :class="scrolled ? 'text-gray-600 hover:text-black' : 'text-white/80 hover:text-white'" class="relative inline-flex items-center p-2 transition-colors" title="Accedi Shop / Registrati">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
            </a>
        @endauth
    @endif

    @if($shopEnabled == '1')
        <a href="{{ route('public.shop.cart.index') }}" :class="scrolled ? 'text-gray-600 hover:text-primary' : 'text-white/80 hover:text-white'" class="relative inline-flex items-center p-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            @php $cartCount = array_sum(array_column(session()->get('cart', []), 'quantita')); @endphp
            @if($cartCount > 0)
                <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">{{ $cartCount }}</span>
            @endif
        </a>
    @endif
</div>
        </div>
    </div>

    <!-- Mobile menu, toggle via Alpine.js -->
    <div x-show="mobileMenuOpen" class="lg:hidden bg-white border-t border-gray-200" style="display: none;"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        <div class="pt-2 pb-3 space-y-1 font-display">


            @if(isset($shared_sezioni))
                @foreach($shared_sezioni->where('mostra_nel_menu', true) as $sez)
                    @if(
                        ($sez->modulo === 'shop' && $shopEnabled != '1') ||
                        ($sez->modulo === 'b2b' && $shopEnabled != '1') ||
                        ($sez->modulo === 'booking' && $bookingEnabled != '1')
                    )
                        @continue
                    @endif
                    @php
                        $url = route('public.sezione', $sez->slug ?? $sez->id.'-it');
                        if ($sez->modulo === 'home') $url = route('public.home');
                        if ($sez->modulo === 'shop') $url = route('public.shop.index');
                        if ($sez->modulo === 'booking') $url = route('public.booking.index');
                        if ($sez->modulo === 'b2b') $url = route('agent.dashboard');

                        $isActive = request()->is($sez->slug ?? $sez->id.'-it') ||
                                    ($sez->modulo === 'home' && request()->routeIs('public.home')) ||
                                    ($sez->modulo === 'shop' && request()->routeIs('public.shop.*')) ||
                                    ($sez->modulo === 'booking' && request()->routeIs('public.booking.*')) ||
                                    ($sez->modulo === 'b2b' && request()->routeIs('agent.*'));
                    @endphp

                    @if($sez->tipo == 'archivio' && $sez->menu_a_tendina && !$sez->modulo)
                        <div x-data="{ openSub: false }">
                            <button @click="openSub = !openSub" class="w-full flex justify-between items-center text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium transition duration-150 ease-in-out">
                                {{ $sez->nome }}
                                <svg class="ml-1 h-4 w-4 transform transition-transform duration-200 text-gray-400" :class="{'rotate-180': openSub}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="openSub" class="bg-gray-50 pl-6 pr-4 py-2 space-y-1" style="display: none;">
                                <a href="{{ $url }}" class="block py-2 text-sm text-gray-700 font-semibold hover:text-indigo-600">
                                    Tutti gli articoli
                                </a>
                                @foreach($sez->articles()->where('visibile', true)->latest()->get() as $articolo)
                                    <a href="{{ route('public.articolo', ['sezione_slug' => $sez->slug ?? $sez->id.'-it', 'articolo_slug' => $articolo->slug ?? $articolo->id.'-it']) }}" class="block py-2 text-sm text-gray-500 hover:text-primary">
                                        {{ Str::limit($articolo->titolo, 30) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $url }}" class="block pl-3 pr-4 py-2 border-l-2 border-primary {{ $isActive ? 'border-text-primary text-primary' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                            {{ $sez->nome }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</header>
