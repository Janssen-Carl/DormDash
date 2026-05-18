@extends('layouts.main')

@section('title', 'Sign Up - DormDash')

@section('content')
    <section
        class="flex min-h-[calc(100vh-80px)] flex-1 items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
        <div
            class="grid w-full max-w-6xl grid-cols-2 overflow-hidden rounded-3xl border border-gray-200 shadow-2xl bg-white">
            {{-- Left Side --}}
            <div
                class="flex flex-col justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 px-12 py-16 text-white relative overflow-hidden">
                {{-- Decorative elements --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full -ml-36 -mb-36"></div>

                <div class="relative z-10">
                    <h1 class="mb-4 text-5xl leading-tight font-bold">Create Your Account</h1>
                    <p class="text-lg leading-relaxed text-emerald-50/80">Get instant access to fresh groceries delivered right to your dorm in just 30 minutes.</p>

                    <div class="mt-12 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-bolt class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Quick Setup</p>
                                <p class="text-sm text-emerald-50/70">Sign up in under 2 minutes</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-gift class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Welcome Bonus</p>
                                <p class="text-sm text-emerald-50/70">Get 20% off your first order</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-lock-closed class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Secure & Private</p>
                                <p class="text-sm text-emerald-50/70">Your data is always protected</p>
                            </div>
                        </div>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="mt-12 pt-8 border-t border-white/20">
                        <p class="text-sm text-emerald-100 mb-3">Progress</p>
                        <div class="flex gap-2">
                            <div class="flex-1 h-1 bg-white rounded-full step-indicator" id="step1Indicator"></div>
                            <div class="flex-1 h-1 bg-white/30 rounded-full step-indicator" id="step2Indicator"></div>
                        </div>
                        <p class="text-sm text-emerald-100 mt-3"><span id="stepNumber">Step 1</span> of 2</p>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex flex-col justify-start bg-gradient-to-b from-white to-gray-50 px-12 py-16 overflow-y-auto">
                <div class="mb-8">
                    <h2 class="mb-2 text-4xl font-bold text-gray-900" id="stepTitle">Tell us about yourself</h2>
                    <p class="text-gray-600 font-medium" id="stepSubtitle">Choose your username and account type</p>
                </div>

                <form method="POST" action="/register" class="space-y-6 flex-1">
                    @csrf

                    {{-- Step 1: Username & Role --}}
                    <div id="step1" class="space-y-6">
                        {{-- Username Field --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Username</label>
                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-user class="h-5 w-5" />
                                </div>
                                <input type="text" name="username" placeholder="Enter username" required autofocus
                                    value="{{ old('username') }}"
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
                            </div>
                            @error('username')
                                <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                    <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Role Field --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">I am a</label>
                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-briefcase class="h-5 w-5" />
                                </div>
                                <select name="role" required
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 transition-all duration-200 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400 appearance-none cursor-pointer">
                                    <option value="customer" @selected(old('role') === 'customer')>Customer</option>
                                    <option value="vendor" @selected(old('role') === 'vendor')>Vendor</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                    <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Step 2: Email & Passwords --}}
                    <div id="step2" class="space-y-6 hidden">
                        {{-- Email Field --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Email Address</label>
                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-envelope class="h-5 w-5" />
                                </div>
                                <input type="email" name="email" placeholder="Enter your email" required
                                    value="{{ old('email') }}"
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
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
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Password</label>
                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none">
                                    <x-heroicon-o-lock-closed class="h-5 w-5" />
                                </div>
                                <input id="password" type="password" name="password" placeholder="••••••••" required
                                    class="pl-12 pr-12 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" />
                                <button type="button" id="togglePassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition-colors duration-200 focus:outline-none"
                                    aria-label="Toggle password visibility">
                                    <x-heroicon-o-eye class="h-5 w-5 hidden" id="eyeOpen" />
                                    <x-heroicon-o-eye-slash class="h-5 w-5" id="eyeSlash" />
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm font-medium text-red-600 flex items-center gap-1">
                                    <x-heroicon-o-exclamation-circle class="h-4 w-4" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Confirm Password Field --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Confirm Password</label>
                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none">
                                    <x-heroicon-o-lock-closed class="h-5 w-5" />
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="••••••••" required
                                    class="pl-12 pr-12 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100" />
                                <button type="button" id="togglePasswordConfirm"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition-colors duration-200 focus:outline-none"
                                    aria-label="Toggle password visibility">
                                    <x-heroicon-o-eye class="h-5 w-5 hidden" id="eyeOpenConfirm" />
                                    <x-heroicon-o-eye-slash class="h-5 w-5" id="eyeSlashConfirm" />
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex gap-3 pt-6" id="buttonsContainer">
                        <button type="button" id="nextBtn"
                            class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 py-3 text-lg font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-emerald-700 hover:to-emerald-600 active:scale-95 shadow-md">
                            <span class="flex items-center justify-center gap-2">
                                Next
                                <x-heroicon-o-arrow-right class="h-5 w-5" />
                            </span>
                        </button>
                        <button type="submit" id="submitBtn"
                            class="hidden w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 py-3 text-lg font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-emerald-700 hover:to-emerald-600 active:scale-95 shadow-md">
                            <span class="flex items-center justify-center gap-2">
                                Create Account
                                <x-heroicon-o-arrow-right class="h-5 w-5" />
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="mb-2 text-sm text-gray-600">Already have an account?</p>
                    <a href="/login"
                        class="inline-flex items-center gap-2 text-lg font-semibold text-emerald-600 hover:text-emerald-700 transition-colors group">
                        Sign in instead
                        <x-heroicon-o-arrow-right class="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                    </a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let currentStep = 1;
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const nextBtn = document.getElementById('nextBtn');
                const submitBtn = document.getElementById('submitBtn');
                const stepTitle = document.getElementById('stepTitle');
                const stepSubtitle = document.getElementById('stepSubtitle');
                const stepNumber = document.getElementById('stepNumber');
                const step1Indicator = document.getElementById('step1Indicator');
                const step2Indicator = document.getElementById('step2Indicator');
                const usernameInput = document.querySelector('input[name="username"]');
                const roleSelect = document.querySelector('select[name="role"]');

                // Password toggle for password field
                const passwordInput = document.getElementById('password');
                const toggleButton = document.getElementById('togglePassword');
                const eyeOpen = document.getElementById('eyeOpen');
                const eyeSlash = document.getElementById('eyeSlash');

                if (toggleButton) {
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
                }

                // Password toggle for confirm password field
                const passwordConfirmInput = document.getElementById('password_confirmation');
                const toggleButtonConfirm = document.getElementById('togglePasswordConfirm');
                const eyeOpenConfirm = document.getElementById('eyeOpenConfirm');
                const eyeSlashConfirm = document.getElementById('eyeSlashConfirm');

                if (toggleButtonConfirm) {
                    toggleButtonConfirm.addEventListener('click', function (e) {
                        e.preventDefault();
                        const isPassword = passwordConfirmInput.type === 'password';

                        if (isPassword) {
                            passwordConfirmInput.type = 'text';
                            eyeOpenConfirm.classList.remove('hidden');
                            eyeSlashConfirm.classList.add('hidden');
                        } else {
                            passwordConfirmInput.type = 'password';
                            eyeOpenConfirm.classList.add('hidden');
                            eyeSlashConfirm.classList.remove('hidden');
                        }
                    });
                }

                // Step navigation
                nextBtn.addEventListener('click', function () {
                    if (currentStep === 1) {
                        // Validate step 1 fields
                        if (!usernameInput.value.trim()) {
                            usernameInput.focus();
                            return;
                        }
                        if (!roleSelect.value) {
                            roleSelect.focus();
                            return;
                        }
                        currentStep = 2;
                        updateStep();
                    }
                });

                function updateStep() {
                    if (currentStep === 1) {
                        step1.classList.remove('hidden');
                        step2.classList.add('hidden');
                        nextBtn.classList.remove('hidden');
                        submitBtn.classList.add('hidden');
                        stepTitle.textContent = 'Tell us about yourself';
                        stepSubtitle.textContent = 'Choose your username and account type';
                        stepNumber.textContent = 'Step 1';
                        step1Indicator.classList.remove('bg-white/30');
                        step1Indicator.classList.add('bg-white');
                        step2Indicator.classList.add('bg-white/30');
                        step2Indicator.classList.remove('bg-white');
                    } else if (currentStep === 2) {
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        nextBtn.classList.add('hidden');
                        submitBtn.classList.remove('hidden');
                        stepTitle.textContent = 'Secure your account';
                        stepSubtitle.textContent = 'Create a strong password';
                        stepNumber.textContent = 'Step 2';
                        step1Indicator.classList.remove('bg-white');
                        step1Indicator.classList.add('bg-white/30');
                        step2Indicator.classList.remove('bg-white/30');
                        step2Indicator.classList.add('bg-white');
                    }
                }
            });
        </script>
    </section>

@endsection
