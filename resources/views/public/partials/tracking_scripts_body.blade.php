@php
    $gtmId = $global_settings['tracking_gtm'] ?? '';
    $fbPixelId = $global_settings['tracking_facebook_pixel'] ?? '';
@endphp

<!-- Google Tag Manager (noscript) -->
@if(!empty($gtmId))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

<!-- Facebook Pixel (noscript) -->
@if(!empty($fbPixelId))
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"
    /></noscript>
@endif
