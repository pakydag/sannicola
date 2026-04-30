<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $favicon = \App\Models\Setting::where('key', 'site_favicon')->value('value');
        @endphp

        @if($favicon)
            <link rel="icon" type="image/x-icon" href="{{ asset($favicon) }}">
        @endif

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased overflow-hidden">
        <div class="flex h-screen bg-gray-100 flex-col md:flex-row">
            
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header (Optional Mobile Toggle & Profile dropdown) -->
                <header class="bg-white shadow z-10 hidden md:flex justify-end items-center px-6 py-4 border-b">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-4">{{ Auth::user()->name }}</span>
                        <!-- Logout form -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-medium">Esci</button>
                        </form>
                    </div>
                </header>

                <!-- Page Heading (Title bar per le singole viste) -->
                @isset($header)
                    <div class="bg-white shadow-sm border-b">
                        <div class="max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Scrollable Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
