@extends('layouts.main')

@section('title', 'Forgot Password - DormDash')

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
                    <h1 class="mb-4 text-5xl leading-tight font-bold">Recover Account</h1>
                    <p class="text-lg leading-relaxed text-emerald-50/80">Don't worry! Enter your registered email address and we'll send you a link to reset your password securely.</p>

                    <div class="mt-12 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-envelope class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Email Recovery</p>
                                <p class="text-sm text-emerald-50/70">Receive a link in your mailbox</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 mt-1">
                                <x-heroicon-o-shield-check class="h-5 w-5 text-emerald-100" />
                            </div>
                            <div>
                                <p class="font-semibold text-white">Secure Reset</p>
                                <p class="text-sm text-emerald-50/70">Your security is our priority</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex flex-col justify-start bg-gradient-to-b from-white to-gray-50 px-16 py-20">
                <div class="mb-10">
                    <h2 class="mb-2 text-4xl font-bold text-gray-900">Forgot Password</h2>
                    <p class="text-gray-600 font-medium">Verify your email to reset password</p>
                </div>

                <form method="POST" action="/forgot-password" class="space-y-8">
                    @csrf
                    {{-- Email Field --}}
                    <div>
                        @if(session('success'))
                            <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 flex items-start gap-2.5 shadow-sm animate-fade-in">
                                <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-medium text-emerald-800 leading-snug">{{ session('success') }}</p>
                            </div>
                        @endif

                        @error('email')
                            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-3.5 flex items-start gap-2.5 shadow-sm animate-fade-in">
                                <x-heroicon-o-exclamation-circle class="h-5 w-5 text-red-600 shrink-0 mt-0.5" />
                                <p class="text-sm font-medium text-red-800 leading-snug">{{ $message }}</p>
                            </div>
                        @enderror

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
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 py-3 text-lg font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-emerald-700 hover:to-emerald-600 active:scale-95 shadow-md">
                        <span class="flex items-center justify-center gap-2">
                            Send Reset Link
                            <x-heroicon-o-arrow-right class="h-5 w-5" />
                        </span>
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="mb-2 text-sm text-gray-600">Remember your password?</p>
                    <a href="/login"
                        class="inline-flex items-center gap-2 text-lg font-semibold text-emerald-600 hover:text-emerald-700 transition-colors group">
                        Back to Login
                        <x-heroicon-o-arrow-right class="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
