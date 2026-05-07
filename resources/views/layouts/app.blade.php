<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased selection:bg-brand-500/30 selection:text-brand-200">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <div class="pt-24 pb-12">
                <!-- Page Heading -->
                @isset($header)
                    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                        <div class="rounded-[2rem] py-8 px-10 bg-transparent border border-white/10">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
