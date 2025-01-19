<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Undercover') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('scripts')
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.partials.header')

    <main class="container my-4">
        @yield('content')
    </main>

    @if(session()->has('message'))
        <div class="alert alert-info alert-dismissible container fade show position-absolute bottom-0 start-0 end-0 z-1" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert  alert-danger alert-dismissible container fade show position-absolute bottom-0 start-0 end-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-auto">
        @include('layouts.partials.footer')
    </div>
</body>
</html>
