<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    </head>

    <body>
        @extends('layouts.main')

        @section('title', 'Login - DormDash')

        @section('content')
            <section class="flex min-h-[calc(100vh-80px)] flex-1 items-center justify-center">
                <div
                    class="grid w-full max-w-3xl grid-cols-2 overflow-hidden rounded-2xl border border-gray-200 shadow-sm"
                >
                    <div class="flex flex-col justify-center bg-emerald-50 px-10 py-14">
                        <p class="mb-1 text-[11px] font-semibold tracking-widest text-emerald-600 uppercase">
                            DormDash
                        </p>
                        <h1 class="mb-3 text-3xl leading-tight font-bold text-emerald-900">Welcome back</h1>
                        <p class="text-sm leading-relaxed text-emerald-700/60">Log in back to continue shopping.</p>
<!-- 
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-emerald-100 px-4 py-3">
                                <p class="text-lg font-bold text-emerald-800">500+</p>
                                <p class="text-xs text-emerald-700/60">Students served</p>
                            </div>
                            <div class="rounded-xl bg-emerald-100 px-4 py-3">
                                <p class="text-lg font-bold text-emerald-800">30 min</p>
                                <p class="text-xs text-emerald-700/60">Avg. delivery</p>
                            </div>
                            <div class="rounded-xl bg-emerald-100 px-4 py-3">
                                <p class="text-lg font-bold text-emerald-800">100+</p>
                                <p class="text-xs text-emerald-700/60">Products</p>
                            </div>
                            <div class="rounded-xl bg-emerald-100 px-4 py-3">
                                <p class="text-lg font-bold text-emerald-800">4.9★</p>
                                <p class="text-xs text-emerald-700/60">Avg. rating</p>
                            </div>
                        </div> -->

                        <div class="mt-10 border-t border-emerald-200 pt-6">
                            <p class="mb-1 text-xs text-emerald-700/40">Don't have an account?</p>
                            <a
                                href="/register"
                                class="text-sm font-semibold text-emerald-500 transition-colors hover:text-emerald-400"
                            >
                                Sign up here →
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center bg-white px-10 py-14">
                        <h2 class="mb-7 text-lg font-semibold text-zinc-900">Log in to your account</h2>

                        <form method="POST" action="/login">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-1.5 block text-xs font-medium tracking-wide text-zinc-400 uppercase">
                                    Email Address
                                </label>
                                <flux:input
                                    type="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    required
                                    autofocus
                                    value="{{ old('email') }}"
                                    class="rounded-lg border border-zinc-300 bg-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                />
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <div class="mb-1.5 flex items-center justify-between">
                                    <label class="text-xs font-medium tracking-wide text-zinc-400 uppercase">
                                        Password
                                    </label>
                                </div>
                                <flux:input
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    class="rounded-lg border border-zinc-300 bg-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                />
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                                <a
                                    href=""
                                    class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700"
                                >
                                    Forgot password?
                                </a>
                            </div>

                            <button
                                type="submit"
                                class="mt-6 h-11 w-full rounded-xl bg-emerald-600 text-sm font-semibold text-white transition-colors hover:bg-emerald-700"
                            >
                                Log In
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        @endsection
