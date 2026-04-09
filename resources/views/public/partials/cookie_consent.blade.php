@php
    $consentEnabled = ($global_settings['cookie_consent_enabled'] ?? '0') === '1';
    $consentText = $global_settings['cookie_consent_text'] ?? 'Questo sito utilizza i cookie per migliorare l\'esperienza di navigazione. Continuando accetti il loro utilizzo.';
@endphp

@if($consentEnabled)
<div x-data="{ 
        visible: false,
        accept() {
            document.cookie = 'cookie_consent=accepted; expires=Fri, 31 Dec 2030 23:59:59 GMT; path=/; SameSite=Lax';
            this.visible = false;
            window.location.reload();
        },
        reject() {
            document.cookie = 'cookie_consent=rejected; expires=Fri, 31 Dec 2030 23:59:59 GMT; path=/; SameSite=Lax';
            this.visible = false;
        }
    }"
    x-init="setTimeout(() => { if (!document.cookie.includes('cookie_consent=')) visible = true }, 1000)"
    x-show="visible"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-20"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed bottom-4 left-4 right-4 md:left-auto md:max-w-md z-[100]"
    style="display: none;"
>
    <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-gray-900/10 p-6 border-l-4 border-indigo-600">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 bg-indigo-50 p-2 rounded-lg">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-1">Informativa Cookie</h4>
                <p class="text-sm leading-relaxed text-gray-600">
                    {{ $consentText }}
                </p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="reject()" class="text-xs font-semibold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                        Rifiuta
                    </button>
                    <button @click="accept()" class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 transition-all uppercase tracking-widest">
                        Accetto tutto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
