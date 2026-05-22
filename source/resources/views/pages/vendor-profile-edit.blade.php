@extends('layouts.vendor-main')

@section('title', 'Edit Vendor Profile')

@section('content')
@php
    $user = $user ?? auth()->user();
    $profile = $profile ?? $user?->vendor;
    $address = $profile?->address;
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

                <form class="space-y-6">
                    {{-- Personal Information Section --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Personal Information</h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Full Name</label>
                                <input
                                    type="text"
                                    value="Juan Dela Cruz"
                                    placeholder="Enter your full name"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Email Address</label>
                                <input
                                    type="email"
                                    value="juan@example.com"
                                    placeholder="Enter your email"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Phone Number</label>
                                <input
                                    type="tel"
                                    value="+63 912 345 6789"
                                    placeholder="Enter your phone number"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Default Delivery Address Section --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Default Delivery Address</h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Address</label>
                                <input
                                    type="text"
                                    value="Room 123, Dormitory A, University Campus"
                                    placeholder="Enter your address"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">City</label>
                                    <input
                                        type="text"
                                        value="Metro Manila"
                                        placeholder="Enter city"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Country</label>
                                    <input
                                        type="text"
                                        value="Philippines"
                                        placeholder="Enter country"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 border-green-200 bg-green-50 hover:bg-green-100 transition-all duration-200">
                                    <input type="checkbox" checked class="rounded border-green-300 text-green-600 focus:ring-green-600" />
                                    <span class="font-semibold text-gray-700">Set as default delivery address</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Password Change Section --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Change Password</h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Current Password</label>
                                <input
                                    type="password"
                                    placeholder="Enter current password"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">New Password</label>
                                <input
                                    type="password"
                                    placeholder="Enter new password"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Confirm New Password</label>
                                <input
                                    type="password"
                                    placeholder="Confirm new password"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 pt-6">
                        <a href="/vendor-profile" class="flex-1 inline-flex items-center justify-center rounded-xl border border-zinc-200 px-6 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 transition-all">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <form class="space-y-6" action="/vendor-profile" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                <h3 class="mb-8 text-lg font-bold text-gray-900">Vendor Information</h3>

                <div class="space-y-5">
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Brand Name</label>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username', $profile?->name ?? $user?->username ?? '') }}"
                            placeholder="Enter your brand name"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            required
                        />
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $profile?->email ?? $user?->email ?? '') }}"
                            placeholder="Enter your vendor email"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            required
                        />
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Phone Number</label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $profile?->phone ?? '') }}"
                            placeholder="Enter your vendor phone number"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                        />
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Website Link</label>
                        <input
                            type="url"
                            name="website"
                            value="{{ old('website', $profile?->website ?? '') }}"
                            placeholder="https://example.com"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                        />
                        @error('website')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900" />
                        @error('profile_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                <h3 class="mb-8 text-lg font-bold text-gray-900">Business Address</h3>

                <div class="space-y-5">
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Street Address</label>
                        <input
                            type="text"
                            name="street"
                            value="{{ old('street', $address?->street ?? '') }}"
                            placeholder="Enter branch or business street address"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-700">City</label>
                            <input
                                type="text"
                                name="city"
                                value="{{ old('city', $address?->city ?? '') }}"
                                placeholder="Enter city"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-700">Province / State</label>
                            <input
                                type="text"
                                name="province_state"
                                value="{{ old('province_state', $address?->province_state ?? '') }}"
                                placeholder="Enter province or state"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-700">Postal Code</label>
                            <input
                                type="text"
                                name="postal_code"
                                value="{{ old('postal_code', $address?->postal_code ?? '') }}"
                                placeholder="Enter postal code"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-semibold text-gray-700">Country</label>
                            <input
                                type="text"
                                name="country"
                                value="{{ old('country', $address?->country ?? '') }}"
                                placeholder="Enter country"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-6">
                <a href="{{ route('vendor.profile') }}" class="inline-flex flex-1 items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                    Cancel
                </a>
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600">
                    <x-heroicon-o-check class="mr-2 h-4 w-4" />
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
