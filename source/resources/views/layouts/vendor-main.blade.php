<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <style>[x-cloak] { display: none !important; }</style>

        <title>@yield('title', 'DormDash')</title>

        <!-- @fluxAppearance -->
    </head>
    <body>
        @include('components.vendor-app-header')

        <main class="min-h-[calc(100vh-80px)]">
            @yield('content')
        </main>

        @include('components.footer')

        @livewireScripts
        @fluxScripts
    </body>
</html>
