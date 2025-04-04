<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ secure_asset("img/favicon.png") }}" />
        <link rel="icon" type="image/png" href="{{ secure_asset("img/favicon.png") }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>{{ $title ?? "" }} | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="{{ setting("meta_description") }}" />
        <meta name="keyword" content="{{ setting("meta_keyword") }}" />
        @include("frontend.includes.meta")

        <!-- Shortcut Icon -->
        <link rel="shortcut icon" href="{{ secure_asset("img/favicon.png") }}" />
        <link rel="icon" type="image/ico" href="{{ secure_asset("img/favicon.png") }}" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @livewireStyles

        @vite(["resources/css/app-frontend.css"])
        @vite(["resources/js/app-frontend.js"])

        @stack("after-styles")

        <x-google-analytics />
    </head>

    <body>
        @include("frontend.includes.header")

        <main class="">
            {{ $slot }}
        </main>

        @include("frontend.includes.footer")

        <!-- Scripts -->
        @livewireScripts
        @stack("after-scripts")
    </body>
</html>
