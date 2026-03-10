document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.getElementById('accessibility-trigger');
    const panel = document.getElementById('accessibility-panel');
    const closeBtn = document.querySelector('.acc-close');
    const body = document.body;

    // Create reading mask element
    const mask = document.createElement('div');
    mask.id = 'acc-reading-mask';
    body.appendChild(mask);

    // Initial state from localStorage
    const settings = JSON.parse(localStorage.getItem('acc-settings')) || {
        fontSize: 100,
        highContrast: false,
        highlightLinks: false,
        largeCursor: false,
        stopAnimations: false,
        readingMask: false
    };

    const applySettings = () => {
        body.style.fontSize = settings.fontSize + '%';
        body.classList.toggle('acc-high-contrast', settings.highContrast);
        body.classList.toggle('acc-highlight-links', settings.highlightLinks);
        body.classList.toggle('acc-large-cursor', settings.largeCursor);
        body.classList.toggle('acc-stop-animations', settings.stopAnimations);
        body.classList.toggle('acc-reading-mask', settings.readingMask);

        // Update UI buttons
        document.querySelectorAll('.acc-btn[data-setting]').forEach(btn => {
            const setting = btn.dataset.setting;
            if (setting !== 'fontSizePlus' && setting !== 'fontSizeMinus') {
                btn.classList.toggle('active', settings[setting]);
            }
        });

        localStorage.setItem('acc-settings', JSON.stringify(settings));
    };

    applySettings();

    // Toggle Panel
    trigger?.addEventListener('click', () => panel.classList.add('open'));
    closeBtn?.addEventListener('click', () => panel.classList.remove('open'));

    // Handle Setting Clicks
    document.querySelectorAll('.acc-btn[data-setting]').forEach(btn => {
        btn.addEventListener('click', () => {
            const setting = btn.dataset.setting;

            if (setting === 'fontSizePlus') {
                settings.fontSize = Math.min(settings.fontSize + 10, 200);
            } else if (setting === 'fontSizeMinus') {
                settings.fontSize = Math.max(settings.fontSize - 10, 80);
            } else if (setting === 'reset') {
                Object.assign(settings, {
                    fontSize: 100,
                    highContrast: false,
                    highlightLinks: false,
                    largeCursor: false,
                    stopAnimations: false,
                    readingMask: false
                });
            } else {
                settings[setting] = !settings[setting];
            }

            applySettings();
        });
    });

    // Reading mask movement
    document.addEventListener('mousemove', (e) => {
        if (settings.readingMask) {
            const y = e.clientY;
            const height = 100; // mask height
            mask.style.clipPath = `polygon(0% 0%, 0% 100%, 100% 100%, 100% 0%, 0% 0%, 0% ${y - height / 2}px, 100% ${y - height / 2}px, 100% ${y + height / 2}px, 0% ${y + height / 2}px, 0% ${y - height / 2}px)`;
        }
    });
});
