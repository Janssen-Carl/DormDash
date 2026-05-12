@extends('layouts.main')

@section('title', 'Sign Up - DormDash')

@section('content')
            <section class="flex min-h-[calc(100vh-80px)] flex-1 items-center justify-center px-4">
                <div
                    class="grid w-full max-w-3xl grid-cols-2 overflow-hidden rounded-2xl border border-gray-200 shadow-sm"
                >
                    <div class="flex flex-col justify-center bg-emerald-50 px-10 py-14">
                        <p class="mb-1 text-[11px] font-semibold tracking-widest text-emerald-600 uppercase">
                            DormDash
                        </p>
                        <h1 class="mb-3 text-3xl leading-tight font-bold text-emerald-900">
                            Create your
                            <br />
                            account
                        </h1>
                        <p class="text-sm leading-relaxed text-emerald-700/60">
                            Start enjoying our grocery delivery service today.
                        </p>

                        <ul class="mt-6 space-y-2.5">
                            @foreach (['Fast grocery delivery', 'Track your orders live', 'Exclusive dorm deals'] as $perk)
                                <li class="flex items-center gap-2.5">
                                    <div
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600"
                                    >
                                        <x-heroicon-s-check class="h-2.5 w-2.5 text-white" />
                                    </div>
                                    <span class="text-xs text-emerald-800">{{ $perk }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-10 border-t border-emerald-200 pt-6">
                            <p class="mb-1 text-xs text-emerald-700/40">Already have an account?</p>
                            <a
                                href="/login"
                                class="text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700"
                            >
                                Log in instead →
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center bg-white px-10 py-10">
                        <h2 class="mb-6 text-lg font-semibold text-zinc-900">Sign up for free</h2>

                        <form method="POST" action="/register">
                            @csrf
                            <div class="mb-4">
                                <label class="mb-1.5 block text-xs font-semibold tracking-wide text-zinc-400 uppercase">
                                    Username
                                </label>
                                <input
                                    type="text"
                                    name="username"
                                    placeholder="juan.delacruz"
                                    required
                                    autofocus
                                    value="{{ old('username') }}"
                                    class="h-11 w-full rounded-lg border border-zinc-300 bg-white px-4 text-sm text-zinc-900 placeholder-zinc-300 transition outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('username') border-red-500 @enderror"
                                />
                                @error('username')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-4">
                                <label class="mb-1.5 block text-xs font-semibold tracking-wide text-zinc-400 uppercase">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    required
                                    value="{{ old('email') }}"
                                    class="h-11 w-full rounded-lg border border-zinc-300 bg-white px-4 text-sm text-zinc-900 placeholder-zinc-300 transition outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('email') border-red-500 @enderror"
                                />
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="mb-1.5 block text-xs font-semibold tracking-wide text-zinc-400 uppercase">
                                    I am a
                                </label>
                                <select
                                    name="role"
                                    required
                                    class="h-11 w-full rounded-lg border border-zinc-300 bg-white px-4 text-sm text-zinc-900 transition outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                >
                                    <option value="">Select your account type</option>
                                    <option value="customer" @selected(old('role') === 'customer')>Customer</option>
                                    <option value="vendor" @selected(old('role') === 'vendor')>Vendor</option>
                                </select>
                                @error('role')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="mb-1.5 block text-xs font-semibold tracking-wide text-zinc-400 uppercase">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    class="h-11 w-full rounded-lg border border-zinc-300 bg-white px-4 text-sm text-zinc-900 placeholder-zinc-300 transition outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('password') border-red-500 @enderror"
                                />
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="mb-1.5 block text-xs font-semibold tracking-wide text-zinc-400 uppercase">
                                    Confirm Password
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                    class="h-11 w-full rounded-lg border border-zinc-300 bg-white px-4 text-sm text-zinc-900 placeholder-zinc-300 transition outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                />
                            </div>

                            <button
                                type="submit"
                                class="h-11 w-full rounded-xl bg-emerald-600 text-sm font-semibold text-white transition-colors hover:bg-emerald-700"
                            >
                                Create Account
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        @endsection
