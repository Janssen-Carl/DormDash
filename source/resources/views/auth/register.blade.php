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
                        <div class="flex gap-2" id="indicators-container">
                            <div class="flex-1 h-1 bg-white rounded-full step-indicator" id="step1Indicator"></div>
                            <div class="flex-1 h-1 bg-white/30 rounded-full step-indicator" id="step2Indicator"></div>
                            <div class="flex-1 h-1 bg-white/30 rounded-full step-indicator hidden" id="step3Indicator"></div>
                        </div>
                        <p class="text-sm text-emerald-100 mt-3"><span id="stepNumber">Step 1</span> of <span id="totalSteps">2</span></p>
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
                                <select name="role" id="roleSelect" required
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

                    {{-- Step 2: Business Profile (Only shown for vendors) --}}
                    <div id="step2" class="space-y-6 hidden">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-2">Business Profile</h4>
                        </div>
                        
                        {{-- Store Name --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Store Name</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-building-storefront class="h-5 w-5" />
                                </div>
                                <input type="text" id="store_name_input" name="store_name" placeholder="Enter your business/store name"
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
                            </div>
                        </div>

                        {{-- Phone / Contact --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Contact Number</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-phone class="h-5 w-5" />
                                </div>
                                <input type="text" id="store_phone_input" name="store_phone" placeholder="Enter contact number (e.g. 09171234567)"
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
                            </div>
                        </div>

                        {{-- Website URL --}}
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-800">Website URL</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                                    <x-heroicon-o-globe-alt class="h-5 w-5" />
                                </div>
                                <input type="url" id="store_website_input" name="store_website" placeholder="https://yourstore.com (optional)"
                                    class="pl-12 pr-4 w-full rounded-xl border-2 border-gray-300 bg-white py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100 hover:border-gray-400" />
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Email & Passwords (Credentials) --}}
                    <div id="step3" class="space-y-6 hidden">
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
                        <button type="button" id="prevBtn"
                            class="hidden w-1/3 rounded-xl border-2 border-gray-300 bg-white py-3 text-base font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-50 active:scale-95 shadow-sm">
                            Back
                        </button>
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
                const step3 = document.getElementById('step3');

                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const submitBtn = document.getElementById('submitBtn');

                const stepTitle = document.getElementById('stepTitle');
                const stepSubtitle = document.getElementById('stepSubtitle');
                const stepNumber = document.getElementById('stepNumber');
                const totalStepsText = document.getElementById('totalSteps');

                const step1Indicator = document.getElementById('step1Indicator');
                const step2Indicator = document.getElementById('step2Indicator');
                const step3Indicator = document.getElementById('step3Indicator');

                const usernameInput = document.querySelector('input[name="username"]');
                const roleSelect = document.querySelector('select[name="role"]');
                const emailInput = document.querySelector('input[name="email"]');
                const storeNameInput = document.getElementById('store_name_input');
                const storePhoneInput = document.getElementById('store_phone_input');

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

                function getStepFlow() {
                    const isVendor = roleSelect.value === 'vendor';
                    if (isVendor) {
                        return [step1, step2, step3];
                    } else {
                        return [step1, step3];
                    }
                }

                function updateIndicators() {
                    const isVendor = roleSelect.value === 'vendor';
                    const totalSteps = isVendor ? 3 : 2;
                    totalStepsText.textContent = totalSteps;

                    // Show/hide indicator 3
                    if (isVendor) {
                        step3Indicator.classList.remove('hidden');
                    } else {
                        step3Indicator.classList.add('hidden');
                    }

                    // Set progress indicators classes
                    const indicators = [step1Indicator, step2Indicator, step3Indicator];
                    indicators.forEach((indicator, index) => {
                        if (index < totalSteps) {
                            if (index + 1 === currentStep) {
                                indicator.classList.remove('bg-white/30');
                                indicator.classList.add('bg-white');
                            } else {
                                indicator.classList.remove('bg-white');
                                indicator.classList.add('bg-white/30');
                            }
                        }
                    });
                }

                // Step navigation
                nextBtn.addEventListener('click', function () {
                    const steps = getStepFlow();
                    
                    // Validate current step fields before going next
                    if (currentStep === 1) {
                        if (!usernameInput.value.trim()) {
                            usernameInput.focus();
                            return;
                        }
                    } else if (steps[currentStep - 1] === step2) {
                         if (!storeNameInput.value.trim()) {
                             storeNameInput.focus();
                             return;
                         }
                         if (!storePhoneInput.value.trim()) {
                             storePhoneInput.focus();
                             return;
                         }
                    }

                    if (currentStep < steps.length) {
                        currentStep++;
                        updateWizard();
                    }
                });

                prevBtn.addEventListener('click', function () {
                    if (currentStep > 1) {
                        currentStep--;
                        updateWizard();
                    }
                });

                // Reset step tracking when role changes
                roleSelect.addEventListener('change', function () {
                    currentStep = 1;
                    updateWizard();
                });

                function updateWizard() {
                    const steps = getStepFlow();
                    
                    // Hide all steps
                    step1.classList.add('hidden');
                    if (step2) step2.classList.add('hidden');
                    step3.classList.add('hidden');

                    // Show active step
                    const activeStepDiv = steps[currentStep - 1];
                    activeStepDiv.classList.remove('hidden');

                    // Focus first input of active step
                    const firstInput = activeStepDiv.querySelector('input, select');
                    if (firstInput) firstInput.focus();

                    // Update titles & descriptions
                    stepNumber.textContent = `Step ${currentStep}`;
                    
                    if (activeStepDiv === step1) {
                        stepTitle.textContent = 'Tell us about yourself';
                        stepSubtitle.textContent = 'Choose your username and account type';
                    } else if (activeStepDiv === step2) {
                        stepTitle.textContent = 'Business profile';
                        stepSubtitle.textContent = 'Fill in your store details';
                    } else if (activeStepDiv === step3) {
                        stepTitle.textContent = 'Secure your account';
                        stepSubtitle.textContent = 'Provide email and password';
                    }

                    // Update navigation buttons visibility
                    if (currentStep === 1) {
                        prevBtn.classList.add('hidden');
                        nextBtn.classList.remove('hidden');
                        nextBtn.classList.remove('w-2/3');
                        nextBtn.classList.add('w-full');
                        submitBtn.classList.add('hidden');
                    } else {
                        prevBtn.classList.remove('hidden');
                        nextBtn.classList.remove('w-full');
                        nextBtn.classList.add('w-2/3');

                        if (currentStep === steps.length) {
                            nextBtn.classList.add('hidden');
                            submitBtn.classList.remove('hidden');
                            submitBtn.classList.add('w-2/3');
                        } else {
                            nextBtn.classList.remove('hidden');
                            submitBtn.classList.add('hidden');
                        }
                    }

                    // Dynamically set required attributes
                    if (roleSelect.value === 'vendor') {
                        storeNameInput.setAttribute('required', 'required');
                        storePhoneInput.setAttribute('required', 'required');
                    } else {
                        storeNameInput.removeAttribute('required');
                        storePhoneInput.removeAttribute('required');
                    }

                    updateIndicators();
                }

                // Initial setup
                updateWizard();
            });
        </script>
    </section>

@endsection
