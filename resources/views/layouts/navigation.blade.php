<aside class="flex-shrink-0 w-64 bg-slate-900 text-white hidden md:flex flex-col h-full border-r border-slate-800 shadow-xl" x-data="{ open: false }">
    <!-- Logo Sfondo Scuro -->
    <div class="h-16 flex items-center justify-center border-b border-slate-700 px-6 py-4 bg-slate-950">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wider text-white flex items-center gap-2">
            <x-application-logo class="block h-8 w-auto fill-current text-indigo-400" />
            <span class="text-sm truncate">Admin Panel</span>
        </a>
    </div>

    <!-- Menù di Navigazione Scrollabile -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1" aria-label="Sidebar">
        @php 
            $shop_enabled = \App\Models\Setting::where('key', 'shop_enabled')->value('value') == '1'; 
            $booking_enabled = \App\Models\Setting::where('key', 'booking_enabled')->value('value') == '1';
        @endphp

        <!-- Link Singolo: Dashboard -->
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
            <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        @if(Auth::check() && Auth::user()->role === 'admin')
            @php
                $user = Auth::user();
            @endphp

            <!-- Categoria: SITO -->
            @if($user->is_super_admin || $user->can_manage_site)
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-bold tracking-wider text-slate-500 uppercase">Sito</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('public.home') }}" target="_blank" class="text-slate-300 hover:bg-slate-800 hover:text-white group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-slate-400 group-hover:text-white mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Vai al Sito Web
                    </a>
                    <a href="{{ route('admin.articoli.index') }}" class="{{ request()->routeIs('admin.articoli.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Articoli</span>
                    </a>
                    <a href="{{ route('admin.home.edit') }}" class="{{ request()->routeIs('admin.home.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Home Page Editor</span>
                    </a>
                    <a href="{{ route('admin.sezioni.index') }}" class="{{ request()->routeIs('admin.sezioni.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Sezioni Sito</span>
                    </a>
                    <a href="{{ route('admin.global-widgets.index') }}" class="{{ request()->routeIs('admin.global-widgets.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Widget Globali</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Categoria: SHOP (condizionale) -->
            @if($shop_enabled && ($user->is_super_admin || $user->can_manage_shop))
            <div class="pt-4 pb-2 border-t border-slate-800/50 mt-2">
                <p class="px-3 text-xs font-bold tracking-wider text-slate-500 uppercase">Shop</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.shop.categorie.index') }}" class="{{ request()->routeIs('admin.shop.categorie.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Categorie</span>
                    </a>
                    <a href="{{ route('admin.shop.collezioni.index') }}" class="{{ request()->routeIs('admin.shop.collezioni.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Collezioni</span>
                    </a>
                    <a href="{{ route('admin.shop.prodotti.index') }}" class="{{ request()->routeIs('admin.shop.prodotti.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Prodotti & Inventario</span>
                    </a>
                    <a href="{{ route('admin.shop.clienti.index') }}" class="{{ request()->routeIs('admin.shop.clienti.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Clienti (B2C & B2B)</span>
                    </a>
                    <a href="{{ route('admin.shop.ordini.index') }}" class="{{ request()->routeIs('admin.shop.ordini.*') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <svg class="text-indigo-400 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Gestione Ordini
                    </a>
                </div>
            </div>
            @endif

            <!-- Categoria: BOOKING (condizionale) -->
            @if($booking_enabled && ($user->is_super_admin || $user->can_manage_booking))
            <div class="pt-4 pb-2 border-t border-slate-800/50 mt-2">
                <p class="px-3 text-xs font-bold tracking-wider text-slate-500 uppercase">Booking</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.booking.structures.index') }}" class="{{ request()->routeIs('admin.booking.structures.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Strutture</span>
                    </a>
                    <a href="{{ route('admin.booking.bookings.index') }}" class="{{ request()->routeIs('admin.booking.bookings.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Prenotazioni</span>
                    </a>
                    <a href="{{ route('admin.booking.customers.index') }}" class="{{ request()->routeIs('admin.booking.customers.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Clienti Booking</span>
                    </a>
                    <a href="{{ route('admin.booking.calendar') }}" class="{{ request()->routeIs('admin.booking.calendar') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <svg class="text-indigo-400 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Calendario
                    </a>
                    <a href="{{ route('admin.booking.services.index') }}" class="{{ request()->routeIs('admin.booking.services.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Servizi</span>
                    </a>
                    <a href="{{ route('admin.booking.extras.index') }}" class="{{ request()->routeIs('admin.booking.extras.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                        <span class="ml-7">Servizi Extra</span>
                    </a>
                </div>
            </div>
            @endif

             <!-- Link Singoli Inferiori -->
            <div class="pt-4 pb-2 border-t border-slate-800/50 mt-2 space-y-1">
                @if($user->is_super_admin || $user->can_manage_site)
                <a href="{{ route('admin.filemanager') }}" class="{{ request()->routeIs('admin.filemanager') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                    <svg class="text-slate-400 group-hover:text-white mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    File Manager
                </a>
                
                <a href="{{ route('admin.contatti.index') }}" class="{{ request()->routeIs('admin.contatti.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                    <svg class="text-slate-400 group-hover:text-white mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Rubrica Contatti
                </a>
                @endif
                
                @if($user->is_super_admin)
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                    <svg class="text-slate-400 group-hover:text-white mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Amministratori
                </a>
                @endif

                @if($user->is_super_admin || $user->can_manage_voip)
                <a href="{{ route('admin.vapi.index') }}" class="{{ request()->routeIs('admin.vapi.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                    <svg class="text-slate-400 group-hover:text-white mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Agente AI
                </a>
                @endif

                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                    <svg class="text-slate-400 group-hover:text-white mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Configurazione
                </a>
            </div>
        @endif
    </nav>
</aside>

<!-- Mobile Menu Header -->
<div class="md:hidden flex items-center justify-between bg-slate-950 px-4 py-3 border-b border-slate-700 w-full" x-data="{ open: false }">
    <a href="{{ route('dashboard') }}" class="text-white font-bold tracking-wider">
        <x-application-logo class="block h-8 w-auto fill-current text-indigo-400 inline-block mr-2" />
        Admin Panel
    </a>
    <button @click="open = !open" type="button" class="text-slate-400 hover:text-white focus:outline-none focus:text-white">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    
    <!-- Mobile Dropdown -->
    <div x-show="open" @click.away="open = false" class="absolute top-14 left-0 w-full bg-slate-900 border-b border-slate-700 shadow-xl z-50 overflow-y-auto max-h-[80vh]" style="display: none;">
        <nav class="px-2 pt-2 pb-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-slate-800">Dashboard</a>
            
            @if(Auth::check() && Auth::user()->role === 'admin')
                @php $user = Auth::user(); @endphp
                @if($user->is_super_admin || $user->can_manage_site)
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sito</p>
                    <a href="{{ route('public.home') }}" target="_blank" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Vai al Sito Web</a>
                    <a href="{{ route('admin.articoli.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Articoli</a>
                    <a href="{{ route('admin.home.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Home Page Editor</a>
                    <a href="{{ route('admin.sezioni.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Sezioni Sito</a>
                    <a href="{{ route('admin.global-widgets.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Widget Globali</a>
                </div>
                @endif

                @if(\App\Models\Setting::where('key', 'shop_enabled')->value('value') == '1' && ($user->is_super_admin || $user->can_manage_shop))
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Shop</p>
                    <a href="{{ route('admin.shop.categorie.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Categorie</a>
                    <a href="{{ route('admin.shop.collezioni.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Collezioni</a>
                    <a href="{{ route('admin.shop.prodotti.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Prodotti & Inventario</a>
                    <a href="{{ route('admin.shop.clienti.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Clienti</a>
                    <a href="{{ route('admin.shop.ordini.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-400 hover:text-indigo-300 hover:bg-slate-700">Gestione Ordini</a>
                </div>
                @endif

                @if(\App\Models\Setting::where('key', 'booking_enabled')->value('value') == '1' && ($user->is_super_admin || $user->can_manage_booking))
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Booking</p>
                    <a href="{{ route('admin.booking.structures.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Strutture</a>
                    <a href="{{ route('admin.booking.bookings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Prenotazioni</a>
                    <a href="{{ route('admin.booking.calendar') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-400 hover:text-indigo-300 hover:bg-slate-700">Calendario</a>
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-slate-700">
                    @if($user->is_super_admin || $user->can_manage_site)
                    <a href="{{ route('admin.filemanager') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">File Manager</a>
                    <a href="{{ route('admin.contatti.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Rubrica Contatti</a>
                    @endif
                    
                    @if($user->is_super_admin)
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Amministratori</a>
                    @endif
                    
                    @if($user->is_super_admin || $user->can_manage_voip)
                    <a href="{{ route('admin.vapi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Agente AI</a>
                    @endif

                    <a href="{{ route('admin.settings.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-700">Configurazione</a>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-400 hover:text-white hover:bg-red-600">Esci</button>
                    </form>
                </div>
            @endif
        </nav>
    </div>
</div>
