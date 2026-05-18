@extends('layouts.vendor-main')

@section('title', 'Vendor Profile')

@section('content')
@php
    $user = $user ?? auth()->user();
    $profile = $profile ?? $user?->vendor;
    $address = $profile?->address;
    $brandName = $profile?->name ?? $user?->username ?? 'Vendor';
    $email = $profile?->email ?? $user?->email ?? '-';
    $initials = strtoupper(substr($brandName, 0, 2));
@endphp
    <div class="mx-auto max-w-6xl px-8 py-12">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Vendor Profile</h1>
                <p class="mt-2 text-gray-600">View and manage your vendor information</p>
            </div>
            <a href="{{ route('vendor.profile.edit') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                <x-heroicon-o-pencil class="h-4 w-4" />
                Edit Profile
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                    <div class="flex flex-col items-center text-center">
                        @if($profile?->profile_img)
                            <img src="{{ asset($profile->profile_img) }}" alt="{{ $brandName }} logo" class="h-24 w-24 rounded-full object-cover shadow-2xl" />
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 text-4xl font-bold text-white shadow-2xl">
                                {{ $initials }}
                            </div>
                        @endif

                        <h2 class="mt-8 text-xl font-bold text-gray-900">{{ $brandName }}</h2>
                        <p class="mt-1 text-sm font-semibold {{ $profile?->active ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $profile?->active ? 'Active Vendor' : 'Inactive Vendor' }}
                        </p>
                        <p class="mt-2 text-gray-600">{{ $email }}</p>
                        <p class="mt-1 text-xs text-gray-500">Member since {{ optional($user?->created_at)->format('M Y') ?? '-' }}</p>

                        <form class="mt-8 w-full border-t border-gray-200 pt-6" method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-pink-50 py-3 text-sm font-semibold text-red-600 transition-all duration-200 hover:from-red-100 hover:to-pink-100 hover:border-red-300 hover:shadow-md">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="inline h-4 w-4 mr-2" />
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                    <h3 class="mb-8 text-lg font-bold text-gray-900">Vendor Information</h3>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Brand Name</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600">{{ $brandName }}</div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                    <x-heroicon-o-building-storefront class="h-5 w-5" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Email Address</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600">{{ $email }}</div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <x-heroicon-o-envelope class="h-5 w-5" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Phone Number</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600">{{ $profile?->phone ?: '-' }}</div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                    <x-heroicon-o-phone class="h-5 w-5" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Website Link</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600">{{ $profile?->website ?: '-' }}</div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600">
                                    <x-heroicon-o-globe-alt class="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                    <div class="mb-8 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Vendor Address</h3>
                        <a href="/vendor-profile/vendor-address-add" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300 hover:shadow-md">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            Add Address
                        </a>
                    </div>

                    <div class="rounded-2xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600 text-white">
                                <x-heroicon-o-map-pin class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $address ? 'Business Address' : 'No business address set' }}</p>
                                @if($address)
                                    <p class="mt-3 text-sm font-medium text-gray-700">{{ $address->street }}</p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        {{ collect([$address->city, $address->province_state, $address->postal_code, $address->country])->filter()->join(', ') }}
                                    </p>
                                @else
                                    <p class="mt-3 text-sm text-gray-600">Add a location so customers can identify your store branch or pickup point.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
