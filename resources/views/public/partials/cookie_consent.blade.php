@php
    $consentEnabled = ($global_settings['cookie_consent_enabled'] ?? '0') === '1';
    $consentText = $global_settings['cookie_consent_text'] ?? 'Utilizziamo i cookie per migliorare la tua esperienza e analizzare il traffico.';
@endphp

@if($consentEnabled)
<div x-data="{ 
        visible: false,
        showSettings: false,
        prefs: {
            analytics: true,
            marketing: true
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
        },
        close() {
            this.visible = false;
            this.showSettings = false;
            window.location.reload();
        }
    }"
    x-init="setTimeout(() => { if (!document.cookie.includes('cookie_consent=')) visible = true }, 1000)"
    class="fixed inset-0 z-[2147483647] pointer-events-none flex items-end justify-center sm:items-center sm:justify-end p-4 sm:p-6"
    x-show="visible"
    style="display: none;"
>
    <!-- Overlay per Modal Personalizza -->
    <div x-show="showSettings" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"
         @click="showSettings = false"></div>

    <!-- Card Principale / Banner -->
    <div x-show="!showSettings"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-20 sm:translate-x-20 sm:translate-y-0"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         class="bg-white rounded-2xl shadow-2xl ring-1 ring-gray-900/10 p-6 pointer-events-auto w-full max-w-lg border-l-4 border-indigo-600 relative overflow-hidden"
    >
        <div class="absolute top-0 right-0 p-2">
            <button @click="visible = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-base font-bold text-gray-900 mb-2">Monitoraggio Cookies</h4>
                <p class="text-sm leading-relaxed text-gray-600 mb-6">
                    {{ $consentText }} Selezionando 'Accetto tutti', consenti l'uso di tecnologie per marketing e analisi. 
                    <button @click="showSettings = true" class="text-indigo-600 font-semibold underline underline-offset-4 hover:text-indigo-800 transition-colors">Personalizza le tue scelte</button>.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 font-semibold text-xs tracking-wider uppercase">
                    <button @click="rejectAll()" class="w-full sm:w-auto px-4 py-2 text-gray-500 hover:text-gray-800 transition-colors">Rifiuta tutti</button>
                    <button @click="acceptAll()" class="w-full sm:w-auto bg-gray-900 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-200">Accetta tutti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pannello Personalizza -->
    <div x-show="showSettings"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-3xl shadow-2xl p-8 pointer-events-auto w-full max-w-2xl relative z-10"
         style="display: none;"
    >
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-gray-900">Preferenze Cookie</h3>
            <button @click="showSettings = false" class="text-gray-400 hover:text-gray-900 p-2">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="space-y-6 max-h-[60vh] overflow-y-auto px-1">
            <!-- Categoria: Necessari -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="font-bold text-gray-800 flex items-center gap-2">
                        <span>🍪 Cookie strettamente necessari</span>
                        <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full uppercase">Sempre attivi</span>
                    </label>
                    <div class="relative inline-flex items-center cursor-not-allowed">
                        <div class="w-11 h-6 bg-indigo-600 rounded-full opacity-50"></div>
                        <div class="absolute left-6 top-1 bg-white w-4 h-4 rounded-full"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">Questi cookie sono essenziali per il funzionamento del sito e non possono essere disattivati. Solitamente vengono impostati solo in risposta ad azioni effettuate dall'utente (es. impostazione privacy, login, compilazione moduli).</p>
            </div>

            <!-- Categoria: Analitici -->
            <div class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-indigo-100 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <label class="font-bold text-gray-800">📊 Performance e Analytics</label>
                    <button @click="prefs.analytics = !prefs.analytics" 
                            :class="prefs.analytics ? 'bg-indigo-600' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                        <span :class="prefs.analytics ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">Utilizziamo questi cookie per contare le visite e le fonti di traffico, in modo da poter misurare e migliorare le prestazioni del nostro sito. Ci aiutano a sapere quali sono le pagine più e meno popolari.</p>
            </div>

            <!-- Categoria: Marketing -->
            <div class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-indigo-100 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <label class="font-bold text-gray-800">🎯 Marketing & Target</label>
                    <button @click="prefs.marketing = !prefs.marketing" 
                            :class="prefs.marketing ? 'bg-indigo-600' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                        <span :class="prefs.marketing ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">Questi cookie possono essere impostati tramite il nostro sito dai nostri partner pubblicitari (come Facebook o Google Ads). Possono essere utilizzati per creare un profilo dei tuoi interessi e mostrarti annunci pertinenti su altri siti.</p>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
            <button @click="rejectAll()" class="text-xs font-bold text-gray-500 hover:text-gray-900 uppercase tracking-widest">Rifiuta tutti</button>
            <div class="flex gap-3 w-full sm:w-auto">
                <button @click="acceptAll()" class="flex-1 sm:flex-none border-2 border-gray-900 text-gray-900 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-all">Accetta tutti</button>
                <button @click="savePrefs()" class="flex-1 sm:flex-none bg-gray-900 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 shadow-xl transition-all">Salva Scelte</button>
            </div>
        </div>

        <div class="mt-4 text-[10px] text-gray-400 text-center">
            Per maggiori dettagli leggi la nostra <a href="#" class="underline hover:text-gray-600">Privacy Policy</a>
        </div>
    </div>

    <!-- Pulsante per riaprire le preferenze (Cookie Trigger) -->
    <div x-show="!visible && !showSettings" 
         x-init="if (document.cookie.includes('cookie_consent=')) $el.style.display = 'block'"
         class="fixed bottom-6 left-20 z-[2147483646] pointer-events-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-50"
         x-transition:enter-end="opacity-100 scale-100"
         style="display: none;"
    >
        <button @click="visible = true; showSettings = true" 
                class="bg-white/90 backdrop-blur-md p-2.5 rounded-full shadow-xl border border-gray-200 hover:bg-white transition-all group hover:scale-110 active:scale-95"
                title="Gestisci preferenze cookie">
            <svg class="h-5 w-5 text-gray-500 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </button>
    </div>
</div>
@endif
