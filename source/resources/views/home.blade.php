<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <title>Home</title>
    </head>
    <body>
        @extends('layouts.main')

        @section('content')
            <section class="flex min-h-[60vh] flex-col items-center justify-center px-5 text-center">
                <h1 class="text-4xl font-bold tracking-tight text-zinc-900 sm:text-5xl">Welcome to DormDash</h1>

                <p class="mt-4 max-w-md text-base leading-relaxed text-zinc-500 sm:text-lg">
                    Your one-stop grocery solution. Sign up or log in to start shopping!
                </p>

                <div class="mt-10 grid w-full max-w-sm grid-cols-2 gap-3">
                    <a
                        href="/register"
                        class="flex h-12 w-full items-center justify-center rounded-xl border border-emerald-500 bg-white text-sm font-semibold text-emerald-600 transition-colors hover:bg-emerald-50"
                    >
                        Sign Up
                    </a>

                    <a
                        href="/login"
                        class="flex h-12 w-full items-center justify-center rounded-xl bg-emerald-600 text-sm font-semibold text-white transition-colors hover:bg-emerald-700"
                    >
                        Log In
                    </a>
                </div>
            </section>
        @endsection

        @livewireScripts
        @fluxScripts
    </body>
</html>
