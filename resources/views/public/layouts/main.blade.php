
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? config('app.name', 'CRM Laravel') }}</title>

    <!-- Meta SEO Standard -->
    <meta name="description" content="{{ $seo['description'] ?? 'Benvenuti sul nostro sito ufficiale' }}">

    <!-- Open Graph (Facebook, LinkedIn, Pinterest, WhatsApp ecc.) -->
    <meta property="og:title" content="{{ $seo['title'] ?? config('app.name', 'CRM Laravel') }}" />
    <meta property="og:description" content="{{ $seo['description'] ?? 'Benvenuti sul nostro sito ufficiale' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $seo['url'] ?? url()->current() }}" />
    @if(!empty($seo['image']))
        <meta property="og:image" content="{{ asset($seo['image']) }}" />
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? config('app.name', 'CRM Laravel') }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? 'Benvenuti sul nostro sito ufficiale' }}">
    @if(!empty($seo['image']))
        <meta name="twitter:image" content="{{ asset($seo['image']) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus+SC&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tracking Scripts (Head) -->
    @include('public.partials.tracking_scripts_head')
</head>
<body class="font-sans antialiased text-gray-900 bg-white flex flex-col min-h-screen">
    <!-- Tracking Scripts (Body) -->
    @include('public.partials.tracking_scripts_body')

    @php
        // Recupera il primo widget di tipo "top_announcement" creato
        $topAnnouncement = \App\Models\GlobalWidget::where('tipo', 'top_announcement')->first();
    @endphp

    @if($topAnnouncement)
        <div id="top-announcement-container" class="relative z-[60]">
            @include('public.partials.widgets.top_announcement', ['widget' => $topAnnouncement])
        </div>
    @endif

    @include('public.partials.header')

    <!-- Page Content -->
    <main class="flex-grow bg-gray-50">
        @yield('content')
    </main>

    @include('public.partials.footer')

    @php
        $globalSettings = \App\Models\Setting::pluck('value', 'key')->all();
        $accessibilityEnabled = !isset($globalSettings['accessibility_panel_enabled']) || $globalSettings['accessibility_panel_enabled'] == '1';
    @endphp

    @if($accessibilityEnabled)
        <x-accessibility-widget />
    @endif

    @stack('scripts')

    <!-- Cookie Consent Banner (BOTTOM) -->
    @include('public.partials.cookie_consent')
</body>
</html>
