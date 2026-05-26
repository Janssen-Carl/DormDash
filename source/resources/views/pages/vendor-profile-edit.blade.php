@extends('layouts.vendor-main')

@section('title', 'Edit Vendor Profile')

@section('content')
@php
    $user = $user ?? auth()->user();
    $vendor = $vendor ?? $user?->vendor;
@endphp
    <div class="mx-auto max-w-2xl px-8 py-12">
        <div class="mb-8">
            <a href="{{ route('vendor.profile') }}" class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-green-600 transition-colors hover:text-green-700">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Back to Profile
            </a>
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Edit Vendor Profile</h1>
            <p class="mt-2 text-gray-600">Update your brand and vendor contact information</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-3">
                <ul class="space-y-1 text-sm font-medium text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-6" action="/vendor-profile" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Vendor Information --}}
            <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                <h3 class="mb-8 text-lg font-bold text-gray-900">Vendor Information</h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Brand Name</label>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username', $vendor?->name ?? $user?->username ?? '') }}"
                            placeholder="Enter your brand name"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            required
                        />
                        @error('username')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $vendor?->email ?? $user?->email ?? '') }}"
                            placeholder="Enter your vendor email"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            required
                        />
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Phone Number</label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $vendor?->phone ?? '') }}"
                            placeholder="Enter your vendor phone number"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                        />
                        @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Website Link</label>
                        <input
                            type="url"
                            name="website"
                            value="{{ old('website', $vendor?->website ?? '') }}"
                            placeholder="https://example.com"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                        />
                        @error('website')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900" />
                        @error('profile_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                <h3 class="mb-8 text-lg font-bold text-gray-900">Change Password</h3>
                <p class="mb-6 text-sm text-gray-500">Leave blank to keep your current password.</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Current Password</label>
                        <div class="relative">
                            <input
                                :type="showCurrent ? 'text' : 'password'"
                                name="current_password"
                                placeholder="Enter current password"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                            <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <x-heroicon-o-eye x-show="!showCurrent" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="showCurrent" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">New Password</label>
                        <div class="relative">
                            <input
                                :type="showNew ? 'text' : 'password'"
                                name="new_password"
                                id="new_password"
                                placeholder="Enter new password"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                            <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <x-heroicon-o-eye x-show="!showNew" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="showNew" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        <div id="pwRequirements" class="mt-3 space-y-1 text-xs">
                            <p class="text-xs font-semibold text-gray-500 mb-1">Password must contain:</p>
                            <ul class="space-y-1">
                                <li id="reqLength" class="text-gray-400 flex items-center gap-1.5">
                                    <span class="req-icon">○</span> At least 8 characters
                                </li>
                                <li id="reqUpper" class="text-gray-400 flex items-center gap-1.5">
                                    <span class="req-icon">○</span> One uppercase letter
                                </li>
                                <li id="reqLower" class="text-gray-400 flex items-center gap-1.5">
                                    <span class="req-icon">○</span> One lowercase letter
                                </li>
                                <li id="reqNumber" class="text-gray-400 flex items-center gap-1.5">
                                    <span class="req-icon">○</span> One number
                                </li>
                                <li id="reqSpecial" class="text-gray-400 flex items-center gap-1.5">
                                    <span class="req-icon">○</span> One special character (@$!%*?&#...)
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Confirm New Password</label>
                        <div class="relative">
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                name="new_password_confirmation"
                                placeholder="Confirm new password"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <x-heroicon-o-eye x-show="!showConfirm" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="showConfirm" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-6">
                <a href="{{ route('vendor.profile') }}" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                    Cancel
                </a>
                <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600">
                    <x-heroicon-o-check class="mr-2 h-4 w-4" />
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwInput = document.getElementById('new_password');
            if (!pwInput) return;

            const reqLength = document.getElementById('reqLength');
            const reqUpper = document.getElementById('reqUpper');
            const reqLower = document.getElementById('reqLower');
            const reqNumber = document.getElementById('reqNumber');
            const reqSpecial = document.getElementById('reqSpecial');

            pwInput.addEventListener('input', function () {
                const val = this.value;
                const checks = {
                    length: val.length >= 8,
                    upper: /[A-Z]/.test(val),
                    lower: /[a-z]/.test(val),
                    number: /\d/.test(val),
                    special: /[^a-zA-Z0-9\s]/.test(val),
                };

                const reqs = [
                    { el: reqLength, met: checks.length },
                    { el: reqUpper, met: checks.upper },
                    { el: reqLower, met: checks.lower },
                    { el: reqNumber, met: checks.number },
                    { el: reqSpecial, met: checks.special },
                ];

                reqs.forEach(({ el, met }) => {
                    const icon = el.querySelector('.req-icon');
                    if (met) {
                        el.classList.remove('text-gray-400');
                        el.classList.add('text-emerald-600');
                        icon.textContent = '●';
                    } else {
                        el.classList.remove('text-emerald-600');
                        el.classList.add('text-gray-400');
                        icon.textContent = '○';
                    }
                });
            });
        });
    </script>
@endsection
