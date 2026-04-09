@php
    $consentEnabled = ($global_settings['cookie_consent_enabled'] ?? '0') === '1';
    $consentText = $global_settings['cookie_consent_text'] ?? 'Utilizziamo i cookie per migliorare la tua esperienza.';
@endphp

@if($consentEnabled)
<div x-data="{ 
        visible: false,
        showSettings: false,
        hasCookie: false,
        prefs: {
            analytics: true,
            marketing: true
        },
        init() {
            this.hasCookie = document.cookie.includes('cookie_consent=');
            setTimeout(() => {
                if (!this.hasCookie) this.visible = true;
            }, 1000);
        },
        acceptAll() {
            this.setCookie('accepted');
            this.close();
        },
        rejectAll() {
            this.setCookie('rejected');
            this.close();
        },
        savePrefs() {
            this.setCookie(encodeURIComponent(JSON.stringify(this.prefs)));
            this.close();
        },
        setCookie(val) {
            document.cookie = 'cookie_consent=' + val + '; expires=Fri, 31 Dec 2030 23:59:59 GMT; path=/; SameSite=Lax';
            this.hasCookie = true;
        },
        close() {
            this.visible = false;
            this.showSettings = false;
            window.location.reload();
        }
    }"
>
    <!-- 1. Banner Principale (Solo se non sto personalizzando) -->
    <div x-show="visible && !showSettings"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-20"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-lg z-[2147483647]"
         style="display: none;"
    >
        <div class="bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] ring-1 ring-gray-900/10 p-6 border-l-4 border-indigo-600 relative overflow-hidden pointer-events-auto">
            <div class="absolute top-0 right-0 p-2">
                <button @click="visible = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 bg-indigo-50 p-2.5 rounded-xl border border-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900 mb-1">Monitoraggio Cookies</h4>
                    <p class="text-xs leading-relaxed text-gray-600 mb-4">
                        {{ $consentText }} Selezionando 'Accetto tutti', consenti l'uso di tecnologie per marketing e analisi. 
                        <button @click="showSettings = true" class="text-indigo-600 font-bold underline underline-offset-2 hover:text-indigo-800">Personalizza le tue scelte</button>.
                    </p>
                    
                    <div class="flex items-center justify-end gap-3 font-bold text-[10px] tracking-wider uppercase">
                        <button @click="rejectAll()" class="px-3 py-2 text-gray-400 hover:text-gray-700 transition-colors">Rifiuta tutti</button>
                        <button @click="acceptAll()" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-600 transition-all shadow-lg">Accetta tutti</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modal Personalizzazione -->
    <template x-if="showSettings">
        <div class="fixed inset-0 z-[2147483647] flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
            <!-- Overlay -->
            <div x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
                 @click="showSettings = false"></div>

            <!-- Modal Card -->
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-3xl shadow-2xl p-6 sm:p-10 w-full max-w-2xl relative z-10 pointer-events-auto"
            >
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-gray-900">Preferenze Cookie</h3>
                    <button @click="showSettings = false" class="text-gray-400 hover:text-gray-900 p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <!-- Necessari -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <span>🍪 Cookie necessari</span>
                                <span class="text-[9px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded-full uppercase">Sempre ON</span>
                            </label>
                            <div class="w-11 h-6 bg-indigo-600/30 rounded-full relative"><div class="absolute left-6 top-1 bg-white w-4 h-4 rounded-full shadow-sm"></div></div>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed">Essenziali per navigazione e sicurezza.</p>
                    </div>

                    <!-- Analitici -->
                    <div class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-indigo-100 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-gray-800 text-sm">📊 Performance e Analytics</label>
                            <button @click="prefs.analytics = !prefs.analytics" 
                                    :class="prefs.analytics ? 'bg-indigo-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                <span :class="prefs.analytics ? 'translate-x-5' : 'translate-x-0'" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200"></span>
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed">Senza questi cookie non possiamo misurare le visite al sito.</p>
                    </div>

                    <!-- Marketing -->
                    <div class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-indigo-100 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-bold text-gray-800 text-sm">🎯 Marketing & Target</label>
                            <button @click="prefs.marketing = !prefs.marketing" 
                                    :class="prefs.marketing ? 'bg-indigo-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                <span :class="prefs.marketing ? 'translate-x-5' : 'translate-x-0'" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200"></span>
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed">Permettono pubblicità mirata e profilazione.</p>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row items-center justify-end gap-3 text-[10px] font-bold uppercase tracking-widest">
                    <button @click="rejectAll()" class="text-gray-400 hover:text-gray-800 p-2">Rifiuta tutti</button>
                    <button @click="acceptAll()" class="border-2 border-gray-900 text-gray-900 px-5 py-2.5 rounded-xl hover:bg-gray-50">Accetta tutti</button>
                    <button @click="savePrefs()" class="bg-gray-900 text-white px-7 py-3 rounded-xl hover:bg-indigo-600 shadow-xl shadow-indigo-100">Salva Scelte</button>
                </div>
            </div>
        </div>
    </template>

    <!-- 3. Trigger Flottante (Per riaprire) -->
    <div x-show="!visible && !showSettings && hasCookie" 
         class="fixed bottom-6 left-20 z-[2147483646]"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-50"
         x-transition:enter-end="opacity-100 scale-100"
         style="display: none;"
    >
        <button @click="visible = true; showSettings = true" 
                class="bg-white/90 backdrop-blur-md p-2.5 rounded-full shadow-2xl border border-gray-200 hover:bg-white transition-all group hover:scale-110 active:scale-95 pointer-events-auto"
                title="Gestisci preferenze cookie">
            <svg class="h-5 w-5 text-gray-500 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </button>
    </div>
</div>
@endif
