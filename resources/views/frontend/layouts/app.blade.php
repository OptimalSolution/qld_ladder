<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->currentLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link href="{{ asset("img/favicon.png") }}" rel="apple-touch-icon" sizes="76x76" />
        <link type="image/png" href="{{ asset("img/favicon.png") }}" rel="icon" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>@yield("title")</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="{{ setting("meta_description") }}" />
        <meta name="keyword" content="{{ setting("meta_keyword") }}" />
        @include("frontend.includes.meta")

        <!-- Shortcut Icon -->
        <link href="{{ asset("img/favicon.png") }}" rel="shortcut icon" />
        <link type="image/ico" href="{{ asset("img/favicon.png") }}" rel="icon" />
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/img/ttcl-app-icon-192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#010205">


        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

        @vite(["resources/css/app-frontend.css", "resources/js/app-frontend.js"])

        @livewireStyles

        @stack("after-styles")

        <x-google-analytics />
    </head>

    <body class="flex flex-col min-h-screen">
        <x-selected-theme />

        @include("frontend.includes.header")

        <main class="flex-grow bg-white dark:bg-gray-800">
            @yield("content")
        </main>

        @include("frontend.includes.footer")

        <!-- Scripts -->
        @livewireScripts
        @stack("after-scripts")
    </body>
</html>
