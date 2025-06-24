<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css" />
    <!-- Styles / Scripts -->
    <script type="module" src="{{ asset('js/main.js') }}" defer></script>
    <script type="module" src="{{ asset('js/sidebar.js') }}" defer></script>

    @stack('scripts')
</head>

<body>
    <div class="loader"></div>
    <div class="wrapper">
        @include('components.header')
        @include('components.sidebar')
        <div hidden id="ajax-response-message" class="ajax-response-message"></div>
        <div id="main-content" class="content flow-lg">
            @yield('content')
        </div>
    </div>

</body>

</html>