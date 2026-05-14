<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%2316a34a' width='100' height='100' rx='20'/><text x='50' y='70' font-size='70' fill='white' text-anchor='middle' font-weight='bold' font-family='Arial'>D</text></svg>">

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
