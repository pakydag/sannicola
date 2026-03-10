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

    <!-- Scripts -->
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
    
    @include('public.partials.header')

    <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    @include('public.partials.footer')

    <x-accessibility-widget />

    @stack('scripts')
</body>
</html>
