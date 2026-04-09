@php
    $gaId = $global_settings['tracking_ga_analytics'] ?? '';
    $gtmId = $global_settings['tracking_gtm'] ?? '';
    $fbPixelId = $global_settings['tracking_facebook_pixel'] ?? '';
    $consentEnabled = ($global_settings['cookie_consent_enabled'] ?? '0') === '1';
@endphp

<script>
    function checkCookieConsent() {
        @if(!$consentEnabled)
            return true;
        @endif
        return document.cookie.split('; ').some((item) => item.trim().startsWith('cookie_consent=accepted'));
    }

    if (checkCookieConsent()) {
        // --- Google Analytics (gtag.js) ---
        @if(!empty($gaId))
            (function() {
                var script = document.createElement('script');
                script.async = true;
                script.src = "https://www.googletagmanager.com/gtag/js?id={{ $gaId }}";
                document.head.appendChild(script);
                
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            })();
        @endif

        // --- Google Tag Manager ---
        @if(!empty($gtmId))
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $gtmId }}');
        @endif

        // --- Facebook Pixel ---
        @if(!empty($fbPixelId))
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $fbPixelId }}');
            fbq('track', 'PageView');
        @endif
    }
</script>
