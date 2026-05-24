<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <style>[x-cloak] { display: none !important; }</style>

        <title>@yield('title', 'Admin — DormDash')</title>
    </head>
    <body class="bg-gray-50 font-sans antialiased" style="font-family: 'Inter', sans-serif;">

        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            @include('components.admin-sidebar')

            {{-- Main content area --}}
            <div class="flex-1 flex flex-col min-w-0">
                {{-- Top bar --}}
                <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Admin Panel')</h1>
                        <p class="text-xs text-gray-400 mt-0.5">@yield('page-subtitle', 'DormDash Administration')</p>
                    </div>
                    <div class="flex items-center gap-3">

                        <form method="POST" action="/logout" title="Sign Out">
                            @csrf
                            <button type="submit" class="text-white bg-red-500 hover:bg-red-700 transition-colors p-2 rounded-lg" aria-label="Sign Out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </header>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mx-6 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                        @foreach($errors->all() as $e)
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $e }}</p>
                        @endforeach
                    </div>
                @endif

                <main class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
        @fluxScripts
    </body>
</html>
