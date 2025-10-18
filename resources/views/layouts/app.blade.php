<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Wallety') }}</title>
        <link rel="shortcut icon" href="{{ asset('/img/logo-white.svg') }}" type="image/x-icon">


        <script>
            if (localStorage.getItem('dark') === 'true') {
                document.documentElement.classList.add('dark')
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- PWA: manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#4A5568">

        <!-- iOS support -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" href="{{ asset('img/icons/main-icon-x4.png') }}">

        <link rel="stylesheet" href="{{ asset('style.css') }}">

        <style>
            #btnInstallPWA {
                display: none;
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 1000;
            }
        </style>
</head>

<body class="font-sans antialiased">
<!-- PAGE LOADER (always visible, themed correctly) -->
<div id="page-loader" class="fixed inset-0 bg-base-bgLight dark:bg-base-dark z-50 flex items-center justify-center">
    <div class="relative flex justify-center items-center">
        <div class="absolute p-16 border-8 border-t-transparent border-b-transparent border-accent-400 rounded-full animate-spin"></div>
        <x-application-logo class="w-20 h-20 fill-current dark:text-white loading-page-animation"/>
    </div>
</div>

<!-- APP CONTENT (Alpine scope) -->
<div
    x-data="{ darkMode: localStorage.getItem('dark') === 'true' }"
    x-init="
            // sync initial HTML class
            document.documentElement.classList.toggle('dark', darkMode);
            // watch for changes, persist and update HTML
            $watch('darkMode', val => {
                localStorage.setItem('dark', val);
                document.documentElement.classList.toggle('dark', val);
            });
        "
    x-cloak
>
    <!-- loader hide/show logic -->
    <script>
        const loader = document.getElementById('page-loader');
        window.addEventListener('beforeunload', () => loader.classList.remove('hidden'));
        window.addEventListener('pageshow', event => loader.classList.add('hidden'));
    </script>

    <!-- PWA install button -->
    <button id="btnInstallPWA" class="px-4 py-2 bg-indigo-600 text-white rounded shadow-lg hover:bg-indigo-700">
        Install App
    </button>

    <!-- Notifications -->
    <div class="max-w-full flex flex-col gap-2 fixed top-3 transform translate-x-1/2 right-[50%]
                   lg:top-4 lg:translate-x-0 lg:right-8
                   text-sm lg:text-xl text-white px-4 py-2
                   overflow-hidden z-50">
        @if(session('success'))
            <x-success-notification type="success">{{ session('success') }}</x-success-notification>
        @endif
        @if($errors->any())
            @foreach($errors->all() as $e)
                <x-success-notification type="error">{{ $e }}</x-success-notification>
            @endforeach
        @endif
    </div>

    <!-- Main layout -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="flex justify-between max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-100">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="max-w-7xl mx-auto px-2 py-4 sm:p-6 lg:p-8 dark:text-white">
            {{ $slot }}
        </main>

        <div class="flex place-content-center pt-20 pb-5">
            <button class="text-sm bg-base-bgLight dark:bg-base-bg hover:bg-gray-50 dark:hover:bg-gray-800 text-neutral-900 dark:text-white px-4 py-2 rounded-lg shadow">
                Report a Problem
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/register-pwa.js') }}"></script>
</div>
</body>
</html>
