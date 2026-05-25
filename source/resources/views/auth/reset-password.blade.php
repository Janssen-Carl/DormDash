@extends('layouts.main')

@section('title', 'Reset Password - DormDash')

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
                        Account Security
                    </p>
                    <h1 class="mb-4 text-5xl leading-tight font-bold">Reset Password</h1>
                    <p class="text-lg leading-relaxed text-emerald-50/80">Choose a strong password containing at least 8 characters. Make sure it contains uppercase, lowercase, and numeric characters to stay secure.</p>

                    <div class="mt-12 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-lock-closed class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Strong Encryption</p>
                                <p class="text-sm text-emerald-50/70">Your password is securely hashed</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-shield-check class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Protection</p>
                                <p class="text-sm text-emerald-50/70">Guards against unauthorized access</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex flex-col justify-start bg-gradient-to-b from-white to-gray-50 px-16 py-16 overflow-y-auto">
                <div class="mb-8">
                    <h2 class="mb-2 text-4xl font-bold text-gray-900">New Password</h2>
                    <p class="text-gray-600 font-medium">Enter your new credentials</p>
                </div>

                <form method="POST" action="/reset-password" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- Email Display --}}
                    <div>
                        <label class="mb-2.5 block text-sm font-semibold text-gray-800">Account Email</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                                <x-heroicon-o-envelope class="h-5 w-5" />
                            </div>
                            <input type="email" disabled value="{{ $email }}"
                                class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-200 bg-gray-100 py-3.5 text-gray-500 cursor-not-allowed" />
                        </div>
                    </div>

                    {{-- New Password Field --}}
                    <div>
                        <label class="mb-2.5 block text-sm font-semibold text-gray-800">New Password</label>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none">
                                <x-heroicon-o-lock-closed class="h-5 w-5" />
                            </div>

                            <input id="password" type="password" name="password" placeholder="••••••••" required autofocus
                                class="pl-12 pr-12 w-full rounded-xl border-2 border-gray-300 bg-white py-3.5 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" />

                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition-colors duration-200 focus:outline-none"
                                aria-label="Toggle password visibility">
                                <x-heroicon-o-eye class="h-5 w-5 hidden" id="eyeOpen" />
                                <x-heroicon-o-eye-slash class="h-5 w-5" id="eyeSlash" />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs font-semibold text-rose-600 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password Confirmation Field --}}
                    <div>
                        <label class="mb-2.5 block text-sm font-semibold text-gray-800">Confirm New Password</label>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none">
                                <x-heroicon-o-lock-closed class="h-5 w-5" />
                            </div>

                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required
                                class="pl-12 pr-12 w-full rounded-xl border-2 border-gray-300 bg-white py-3.5 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" />

                            <button type="button" id="togglePasswordConfirm"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition-colors duration-200 focus:outline-none"
                                aria-label="Toggle password confirmation visibility">
                                <x-heroicon-o-eye class="h-5 w-5 hidden" id="eyeOpenConfirm" />
                                <x-heroicon-o-eye-slash class="h-5 w-5" id="eyeSlashConfirm" />
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-xs font-semibold text-rose-600 flex items-center gap-1">
                                <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 py-3 text-lg font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-emerald-700 hover:to-emerald-600 active:scale-95 shadow-md mt-4">
                        <span class="flex items-center justify-center gap-2">
                            Reset Password
                            <x-heroicon-o-arrow-right class="h-5 w-5" />
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Main password toggle
                const passwordInput = document.getElementById('password');
                const toggleButton = document.getElementById('togglePassword');
                const eyeOpen = document.getElementById('eyeOpen');
                const eyeSlash = document.getElementById('eyeSlash');

                toggleButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    eyeOpen.classList.toggle('hidden', !isPassword);
                    eyeSlash.classList.toggle('hidden', isPassword);
                });

                // Password confirmation toggle
                const confirmInput = document.getElementById('password_confirmation');
                const toggleConfirm = document.getElementById('togglePasswordConfirm');
                const eyeOpenConfirm = document.getElementById('eyeOpenConfirm');
                const eyeSlashConfirm = document.getElementById('eyeSlashConfirm');

                toggleConfirm.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isPassword = confirmInput.type === 'password';
                    confirmInput.type = isPassword ? 'text' : 'password';
                    eyeOpenConfirm.classList.toggle('hidden', !isPassword);
                    eyeSlashConfirm.classList.toggle('hidden', isPassword);
                });
            });
        </script>
    </section>
@endsection
