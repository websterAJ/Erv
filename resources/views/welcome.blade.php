<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        @if (Route::has('login'))
        <div >
            @auth
                <a href="{{ url('/home') }}" >>{{ __('Home') }}</a>
            @else
                <a href="{{ route('login') }}" >>{{ __('Log in') }}</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">>{{ __('Register') }}</a>
                @endif
            @endauth
        </div>
    @endif
    </body>
</html>
