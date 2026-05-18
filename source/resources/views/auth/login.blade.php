@extends('layouts.main')

@section('title', 'Login - DormDash')

@section('content')
    <section
        class="flex min-h-[calc(100vh-80px)] flex-1 items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
        <div
            class="grid w-full max-w-6xl max-h-[calc(100vh-140px)] grid-cols-2 overflow-hidden rounded-3xl border border-gray-200 shadow-2xl bg-white">
            {{-- Left Side --}}
            <div
                class="flex flex-col justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 px-12 py-16 text-white relative overflow-hidden">
                {{-- Decorative elements --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full -ml-36 -mb-36"></div>

                <div class="relative z-10">
                    <p class="mb-2 text-sm font-semibold tracking-widest text-emerald-100 uppercase">
                        Welcome Back
                    </p>
                    <h1 class="mb-4 text-5xl leading-tight font-bold">Log in to DormDash</h1>
                    <p class="text-lg leading-relaxed text-emerald-50/80">Continue your shopping journey and discover fresh
                        groceries delivered to your dorm.</p>

                    <div class="mt-12 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-bolt class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Fast & Easy</p>
                                <p class="text-sm text-emerald-50/70">Login takes seconds</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-shield-check class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Secure</p>
                                <p class="text-sm text-emerald-50/70">Your data is protected</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-truck class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Fast Delivery</p>
                                <p class="text-sm text-emerald-50/70">30 min or less</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex flex-col justify-start bg-gradient-to-b from-white to-gray-50 px-16 py-20">
                <div class="mb-10">
                    <h2 class="mb-2 text-4xl font-bold text-gray-900">Sign In</h2>
                    <p class="text-gray-600 font-medium">Access your DormDash account</p>
                </div>

                <form method="POST" action="/login" class="space-y-8">
                    @csrf
                    {{-- Email Field --}}
                    <div>
                        <label class="mb-4 block text-sm font-semibold text-gray-800">Email Address</label>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                <x-heroicon-o-envelope class="h-5 w-5" />
                            </div>
                            <input type="email" name="email" placeholder="Enter your email" required autofocus
                                value="{{ old('email') }}"
                                class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-4 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label class="mb-4 block text-sm font-semibold text-gray-800">
                            Password
                        </label>

                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none">
                                <x-heroicon-o-lock-closed class="h-5 w-5" />
                            </div>

                            <input id="password" type="password" name="password" placeholder="••••••••" required
                                class="pl-12 pr-12 w-full rounded-xl border-2 border-gray-300 bg-white py-4 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" />

                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition-colors duration-200 focus:outline-none"
                                aria-label="Toggle password visibility">
                                <x-heroicon-o-eye class="h-5 w-5 hidden" id="eyeOpen" />
                                <x-heroicon-o-eye-slash class="h-5 w-5" id="eyeSlash" />
                            </button>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <a href="#"
                                class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors duration-200">
                                Forgot Password?
                            </a>
                        </div>

                        @error('password')
                            <p class="mt-4 text-sm font-medium text-red-600 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 py-3 text-lg font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-emerald-700 hover:to-emerald-600 active:scale-95 shadow-md">
                        <span class="flex items-center justify-center gap-2">
                            Sign In
                            <x-heroicon-o-arrow-right class="h-5 w-5" />
                        </span>
                    </button>

                    <!-- {{-- Divider --}}
                                <div class="relative my-8">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-200"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="bg-white px-2 text-gray-500">Or continue as</span>
                                    </div>
                                </div> -->

                    {{-- Demo Accounts --}}
                    <!-- <div class="space-y-2 text-center">
                                    <p class="text-xs text-gray-600">Test Accounts:</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" class="rounded-lg border-2 border-gray-200 py-2 text-xs font-medium text-gray-700 transition-all hover:border-emerald-500 hover:bg-emerald-50">
                                            Customer
                                        </button>
                                        <button type="button" class="rounded-lg border-2 border-gray-200 py-2 text-xs font-medium text-gray-700 transition-all hover:border-emerald-500 hover:bg-emerald-50">
                                            Vendor
                                        </button>
                                    </div>
                                </div> -->
                </form>

                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="mb-2 text-sm text-gray-600">Don't have an account?</p>
                    <a href="/register"
                        class="inline-flex items-center gap-2 text-lg font-semibold text-emerald-600 hover:text-emerald-700 transition-colors group">
                        Create account
                        <x-heroicon-o-arrow-right class="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                    </a>
                </div>
            </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const passwordInput = document.getElementById('password');
                const toggleButton = document.getElementById('togglePassword');
                const eyeOpen = document.getElementById('eyeOpen');
                const eyeSlash = document.getElementById('eyeSlash');

                toggleButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isPassword = passwordInput.type === 'password';

                    if (isPassword) {
                        passwordInput.type = 'text';
                        eyeOpen.classList.remove('hidden');
                        eyeSlash.classList.add('hidden');
                    } else {
                        passwordInput.type = 'password';
                        eyeOpen.classList.add('hidden');
                        eyeSlash.classList.remove('hidden');
                    }
                });
            });
        </script>
    </section>

@endsection