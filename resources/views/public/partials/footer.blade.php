<footer class="bg-gray-900 text-white pt-16 pb-8 footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Colonna 1: Brand & Descrizione -->
            <div>
                <div class="mb-6">
                    @if(!empty($global_settings['site_logo']))
                        <img src="{{ asset($global_settings['site_logo']) }}" alt="{{ config('app.name') }}" class="h-20 w-auto object-contain brightness-0 invert">
                    @else
                        <span class="text-2xl font-bold text-white">{{ config('app.name') }}</span>
                    @endif
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    {{ $global_settings['footer_description'] ?? 'La tua soluzione completa per la gestione aziendale e la presenza online professionale.' }}
                </p>
                <!-- Social Icons -->
                <div class="flex space-x-4">
                    @if(!empty($global_settings['social_facebook']))
                        <a href="{{ $global_settings['social_facebook'] }}" target="_blank" class="text-gray-300 hover:text-white transition">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                    @endif
                    @if(!empty($global_settings['social_instagram']))
                        <a href="{{ $global_settings['social_instagram'] }}" target="_blank" class="text-gray-300 hover:text-white transition">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16.4a4.4 4.4 0 110-8.8 4.4 4.4 0 010 8.8zm4.952-9.44a1.108 1.108 0 11-2.216 0 1.108 1.108 0 012.216 0z"/></svg>
                        </a>
                    @endif
                    @if(!empty($global_settings['social_linkedin']))
                        <a href="{{ $global_settings['social_linkedin'] }}" target="_blank" class="text-gray-300 hover:text-white transition">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    @endif
                    @if(!empty($global_settings['social_youtube']))
                        <a href="{{ $global_settings['social_youtube'] }}" target="_blank" class="text-gray-300 hover:text-white transition">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Colonna 2: Navigazione Dinamica -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6 text-white">Navigazione</h3>
                <ul class="space-y-4">
                    <li><a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition text-sm">Home</a></li>
                    @if(isset($shared_sezioni))
                        @foreach($shared_sezioni->where('mostra_nel_footer', true) as $sez)
                            <li>
                                <a href="{{ route('public.sezione', $sez->slug ?? $sez->id.'-it') }}" class="text-gray-300 hover:text-white transition text-sm">
                                    {{ $sez->nome }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Colonna 3: Link Legali -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6 text-white">Supporto & Legale</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-gray-400 hover:text-white transition text-sm">Privacy Policy</a></li>
                    <li><a href="javascript:void(0)" onclick="openCookiePreferences()" class="text-gray-400 hover:text-white transition text-sm">Cookie Policy</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition text-sm">Area Riservata</a></li>
                    @if(config('app.shop_enabled') == '1' || ($global_settings['shop_enabled'] ?? '0') == '1')
                        <li><a href="{{ route('public.shop.index') }}" class="text-gray-400 hover:text-white transition text-sm">Shop Online</a></li>
                    @endif
                </ul>
            </div>

            <!-- Colonna 4: Contatti -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-6 text-white">Contatti</h3>
                <ul class="space-y-4 text-sm text-gray-300">
                    @if(!empty($global_settings['company_address']))
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-white mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $global_settings['company_address'] }}
                        </li>
                    @endif
                    @if(!empty($global_settings['company_phone']))
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-white mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 004.516 4.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:{{ str_replace(' ', '', $global_settings['company_phone']) }}" class="hover:text-white transition">{{ $global_settings['company_phone'] }}</a>
                        </li>
                    @endif
                    @if(!empty($global_settings['company_email_public']))
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-white mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:{{ $global_settings['company_email_public'] }}" class="hover:text-white transition">{{ $global_settings['company_email_public'] }}</a>
                        </li>
                    @endif
                    @if(!empty($global_settings['company_vat']))
                        <li class="pt-4 border-t border-gray-800">
                            P.IVA: {{ $global_settings['company_vat'] }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-gray-800 text-center text-white text-xs">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        </div>
    </div>
</footer>

