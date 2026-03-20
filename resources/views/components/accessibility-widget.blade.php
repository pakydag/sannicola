<div id="accessibility-widget-root">
    <!-- Trigger Button -->
    <button id="accessibility-trigger" title="Impostazioni di accessibilità" aria-label="Apri impostazioni accessibilità">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><path d="M9 22V12h6v10"/><path d="M5 12V7.5a4.5 4.5 0 0 1 9 0V12h3"/></svg>
    </button>

    <!-- Overlay Background -->
    <div id="accessibility-overlay"></div>

    <!-- Panel -->
    <div id="accessibility-panel">
        <div class="acc-header">
            <div>
                <h2>Impostazioni di accessibilità</h2>
                <p>Sviluppato per la tua azienda</p>
            </div>
            <button class="acc-close" title="Chiudi">&times;</button>
        </div>

        <div class="acc-content">
            <div class="acc-section">
                <h3>Seleziona il tuo profilo di accessibilità</h3>
                <div class="acc-profile-list">
                    <div class="acc-profile-item">
                        <div class="acc-profile-info">
                            <strong>Modalità per disabilità visive</strong>
                            <span>Migliora gli elementi visivi del sito web</span>
                        </div>
                        <label class="acc-switch">
                            <input type="checkbox" data-profile="visuallyImpaired">
                            <span class="acc-slider"></span>
                        </label>
                    </div>
                    <div class="acc-profile-item">
                        <div class="acc-profile-info">
                            <strong>Profilo sicuro per crisi</strong>
                            <span>Riduce i lampi e migliora i colori</span>
                        </div>
                        <label class="acc-switch">
                            <input type="checkbox" data-profile="epilepsySafe">
                            <span class="acc-slider"></span>
                        </label>
                    </div>
                    <div class="acc-profile-item">
                        <div class="acc-profile-info">
                            <strong>Modalità adatta per ADHD</strong>
                            <span>Navigazione concentrata senza distrazioni</span>
                        </div>
                        <label class="acc-switch">
                            <input type="checkbox" data-profile="adhdMode">
                            <span class="acc-slider"></span>
                        </label>
                    </div>
                    <div class="acc-profile-item">
                        <div class="acc-profile-info">
                            <strong>Modalità per cecità</strong>
                            <span>Ottimizza il sito per lettori di schermo</span>
                        </div>
                        <label class="acc-switch">
                            <input type="checkbox" data-profile="blindnessMode">
                            <span class="acc-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="acc-section">
                <h3>Moduli di contenuto</h3>
                <div class="acc-modules-grid">
                    <div class="acc-module-card">
                        <span class="acc-module-title">Dimensione del carattere</span>
                        <div class="acc-font-controls">
                            <button data-setting="fontSizeMinus" class="acc-circle-btn">-</button>
                            <span id="acc-font-label">Predefinito</span>
                            <button data-setting="fontSizePlus" class="acc-circle-btn">+</button>
                        </div>
                    </div>
                    <div class="acc-module-card active-on-setting" data-setting="highlightLinks">
                        <button class="acc-full-btn" data-setting="highlightLinks">
                            <i class="icon">🔗</i>
                            <span>Evidenzia i link</span>
                        </button>
                    </div>
                    <div class="acc-module-card active-on-setting" data-setting="highContrast">
                        <button class="acc-full-btn" data-setting="highContrast">
                            <i class="icon">🌓</i>
                            <span>Saturazione</span>
                        </button>
                    </div>
                    <div class="acc-module-card active-on-setting" data-setting="readableFont">
                        <button class="acc-full-btn" data-setting="readableFont">
                            <i class="icon">Aa</i>
                            <span>Font Leggibile</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="acc-footer">
            <button class="acc-reset-btn" data-setting="reset">Ripristina Impostazioni</button>
        </div>
    </div>
</div>

<style>
    :root {
        --acc-brand: #0052ff;
        --acc-brand-light: #e6f0ff;
        --acc-bg: #ffffff;
        --acc-text: #1a1a1b;
        --acc-text-muted: #5d646a;
        --acc-border: #f0f2f4;
        --acc-radius: 16px;
    }

    #accessibility-trigger {
        position: fixed; bottom: 20px; left: 20px; width: 56px; height: 56px;
        background: var(--acc-brand); color: white; border: none; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(0,82,255,0.3); z-index: 1000000;
    }

    #accessibility-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.3); z-index: 1000001; display: none;
    }
    #accessibility-overlay.active { display: block; }

    #accessibility-panel {
        position: fixed; top: 0; left: -420px; width: 400px; height: 100vh;
        background: #f8fafc; z-index: 1000002; transition: left 0.3s ease;
        display: flex; flex-direction: column; box-shadow: 5px 0 25px rgba(0,0,0,0.1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    #accessibility-panel.open { left: 0; }

    .acc-header {
        background: var(--acc-brand); color: white; padding: 30px 24px;
        position: relative;
    }
    .acc-header h2 { font-size: 1.5rem; margin: 0; font-weight: 700; }
    .acc-header p { margin: 8px 0 0 0; font-size: 0.85rem; opacity: 0.8; }
    .acc-close {
        position: absolute; top: 20px; right: 20px; background: none; border: none;
        color: white; font-size: 2rem; cursor: pointer; line-height: 1;
    }

    .acc-content { flex: 1; overflow-y: auto; padding: 24px; }
    .acc-section { margin-bottom: 30px; }
    .acc-section h3 {
        font-size: 0.9rem; font-weight: 700; color: var(--acc-text);
        margin-bottom: 15px; text-transform: none;
    }

    /* Profiles list */
    .acc-profile-list { background: white; border-radius: var(--acc-radius); overflow: hidden; border: 1px solid var(--acc-border); }
    .acc-profile-item {
        padding: 16px; border-bottom: 1px solid var(--acc-border);
        display: flex; justify-content: space-between; align-items: center;
    }
    .acc-profile-item:last-child { border-bottom: none; }
    .acc-profile-info { display: flex; flex-direction: column; gap: 4px; padding-right: 15px; }
    .acc-profile-info strong { font-size: 0.9rem; color: var(--acc-text); }
    .acc-profile-info span { font-size: 0.75rem; color: var(--acc-text-muted); }

    /* Switch toggle */
    .acc-switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
    .acc-switch input { opacity: 0; width: 0; height: 0; }
    .acc-slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc; transition: .4s; border-radius: 24px;
    }
    .acc-slider:before {
        position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
    input:checked + .acc-slider { background-color: var(--acc-brand); }
    input:checked + .acc-slider:before { transform: translateX(20px); }

    /* Modules grid */
    .acc-modules-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .acc-module-card {
        padding: 15px; background: white; border-radius: var(--acc-radius);
        border: 1px solid var(--acc-border); display: flex; flex-direction: column; align-items: center;
        gap: 10px; text-align: center;
    }
    .acc-module-card.active { border-color: var(--acc-brand); background: var(--acc-brand-light); }
    .acc-module-title { font-size: 0.75rem; color: var(--acc-text-muted); font-weight: 600; }
    
    .acc-font-controls { display: flex; align-items: center; gap: 15px; }
    .acc-circle-btn {
        width: 32px; height: 32px; border-radius: 50%; border: none;
        background: var(--acc-brand); color: white; font-weight: bold; cursor: pointer;
    }
    #acc-font-label { font-size: 0.85rem; font-weight: 600; min-width: 80px; }

    .acc-full-btn {
        background: none; border: none; width: 100%; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .acc-full-btn .icon { font-size: 1.4rem; font-style: normal; }
    .acc-full-btn span { font-size: 0.85rem; font-weight: 600; color: var(--acc-text); }

    .acc-footer { padding: 24px; background: white; border-top: 1px solid var(--acc-border); }
    .acc-reset-btn {
        width: 100%; padding: 12px; background: #f1f3f5; border: none;
        border-radius: 8px; font-weight: 700; color: var(--acc-text); cursor: pointer;
    }

    /* Functional Classes */
    body.acc-visually-impaired { saturate: 200%; contrast: 120%; }
    body.acc-epilepsy-safe { --acc-stop-anims: 1; filter: grayscale(50%) brightness(90%); }
    body.acc-blindness-mode [role="button"], body.acc-blindness-mode a { outline: 4px solid var(--acc-brand); }
    
    /* Re-use of previous styles where applicable but scoped better */
    body.acc-high-contrast { background-color: #000 !important; color: #fff !important; }
    body.acc-high-contrast *:not(.acc-slider):not(.acc-close) { background-color: #000 !important; color: #fff !important; border-color: #fff !important; }
    body.acc-highlight-links a { background: #ff0 !important; color: #000 !important; outline: 3px solid #000 !important; }
</style>

<script>
    (function() {
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.getElementById('accessibility-widget-root');
            const STORAGE_KEY = 'acc-settings-v3';
            const body = document.body;

            let settings = JSON.parse(localStorage.getItem(STORAGE_KEY)) || {
                fontSize: 100,
                highContrast: false,
                highlightLinks: false,
                readableFont: false,
                visuallyImpaired: false,
                epilepsySafe: false,
                adhdMode: false,
                blindnessMode: false
            };

            const apply = () => {
                body.style.fontSize = settings.fontSize + '%';
                const fontLabel = document.getElementById('acc-font-label');
                if(fontLabel) fontLabel.innerText = settings.fontSize === 100 ? 'Predefinito' : settings.fontSize + '%';

                body.classList.toggle('acc-high-contrast', settings.highContrast);
                body.classList.toggle('acc-highlight-links', settings.highlightLinks);
                body.classList.toggle('acc-readable-font', settings.readableFont);
                
                // Profiles
                body.classList.toggle('acc-visually-impaired', settings.visuallyImpaired);
                body.classList.toggle('acc-epilepsy-safe', settings.epilepsySafe);
                body.classList.toggle('acc-adhd-mode', settings.adhdMode);
                body.classList.toggle('acc-blindness-mode', settings.blindnessMode);

                // Update switches and cards
                root.querySelectorAll('[data-profile]').forEach(sw => {
                    sw.checked = settings[sw.dataset.profile];
                });
                root.querySelectorAll('.acc-module-card[data-setting]').forEach(card => {
                    card.classList.toggle('active', settings[card.dataset.setting]);
                });

                localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
            };

            // Events
            root.querySelector('#accessibility-trigger').onclick = () => {
                root.querySelector('#accessibility-panel').classList.add('open');
                root.querySelector('#accessibility-overlay').classList.add('active');
            };

            const close = () => {
                root.querySelector('#accessibility-panel').classList.remove('open');
                root.querySelector('#accessibility-overlay').classList.remove('active');
            };
            root.querySelector('.acc-close').onclick = close;
            root.querySelector('#accessibility-overlay').onclick = close;

            root.querySelectorAll('[data-setting]').forEach(btn => {
                btn.onclick = () => {
                    const s = btn.dataset.setting;
                    if(s === 'fontSizePlus') settings.fontSize = Math.min(settings.fontSize + 10, 200);
                    else if(s === 'fontSizeMinus') settings.fontSize = Math.max(settings.fontSize - 10, 70);
                    else if(s === 'reset') settings = { fontSize: 100, highContrast: false, highlightLinks: false, readableFont: false, visuallyImpaired: false, epilepsySafe: false, adhdMode: false, blindnessMode: false };
                    else settings[s] = !settings[s];
                    apply();
                };
            });

            root.querySelectorAll('[data-profile]').forEach(sw => {
                sw.onchange = () => {
                    settings[sw.dataset.profile] = sw.checked;
                    // Auto-toggle some defaults for profiles
                    if(sw.dataset.profile === 'visuallyImpaired' && sw.checked) { settings.fontSize = 120; settings.highContrast = true; }
                    if(sw.dataset.profile === 'adhdMode' && sw.checked) { settings.highlightLinks = true; }
                    apply();
                };
            });

            apply();
        });
    })();
</script>
