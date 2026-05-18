@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
@php
    // Ensure $user and $profile are always available to the view (fallbacks for callers that don't pass them)
    $user = $user ?? auth()->user();
    $profile = $profile ?? null;
    if (!$profile && $user) {
        if ($user->role === 'vendor') {
            $profile = $user->vendor;
        } elseif ($user->role === 'customer') {
            $profile = $user->customer;
        }
    }
@endphp
            <div class="mx-auto max-w-6xl px-8 py-12" x-data="{ showPhotoModal: false }">
                <div class="mb-12 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">My Profile</h1>
                        <p class="mt-2 text-gray-600">View and manage your account information</p>
                    </div>
                    <a href="/profile/edit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                        Edit Profile
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    {{-- Sidebar Profile Card --}}
                    <div class="lg:col-span-1">
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="flex flex-col items-center text-center">
                                {{-- Profile Picture with Edit Button --}}
                                <div class="relative group">
                                    @if(!empty($profile->profile_img))
                                        <img src="{{ asset($profile->profile_img) }}" alt="Profile" class="h-24 w-24 rounded-full object-cover shadow-2xl" />
                                    @else
                                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 text-4xl font-bold text-white shadow-2xl">
                                            {{ strtoupper(substr($user->role === 'vendor' ? ($profile->name ?? $user->username) : ($user->username ?? 'U'), 0, 2)) }}
                                        </div>
                                    @endif

                                    <button 
                                        @click="showPhotoModal = true"
                                        type="button" 
                                        class="absolute bottom-0 right-0 flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white shadow-lg transition-all duration-200 hover:bg-green-700 hover:shadow-xl opacity-0 group-hover:opacity-100"
                                    >
                                        <x-heroicon-o-camera class="h-4 w-4" />
                                    </button>
                                </div>

                                <h2 class="mt-8 text-xl font-bold text-gray-900">{{ $user->role === 'vendor' ? ($profile->name ?? $user->username) : $user->username }}</h2>
                                <p class="mt-1 text-sm text-green-600 font-semibold">{{ $user->role === 'vendor' ? 'Vendor Account' : ucfirst($user->role) . ' Account' }}</p>
                                <p class="mt-2 text-gray-600">{{ $user->role === 'vendor' ? ($profile->email ?? $user->email) : $user->email }}</p>
                                <p class="mt-1 text-xs text-gray-500">Member since {{ optional($user->created_at)->format('M Y') ?? '' }}</p>

                                <div class="mt-8 w-full border-t border-gray-200 pt-6">
                                    <button type="button" class="w-full rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-pink-50 py-3 text-sm font-semibold text-red-600 transition-all duration-200 hover:from-red-100 hover:to-pink-100 hover:border-red-300 hover:shadow-md">
                                        <x-heroicon-o-arrow-right-start-on-rectangle class="inline h-4 w-4 mr-2" />
                                        Sign Out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Personal Information --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <h3 class="mb-8 text-lg font-bold text-gray-900">Personal Information</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $user->role === 'vendor' ? ($profile->name ?? $user->username) : $user->username }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                            <x-heroicon-o-user class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $user->role === 'vendor' ? ($profile->email ?? $user->email) : $user->email }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                            <x-heroicon-o-envelope class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $profile->phone ?? '-' }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                            <x-heroicon-o-phone class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Addresses --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="mb-8 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900">Delivery Addresses</h3>
                                <a href="/address-payment/add" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300 hover:shadow-md">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                    Add Address
                                </a>
                            </div>

                            <div class="space-y-3">
                                <div class="group rounded-2xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 p-5 transition-all duration-200 hover:shadow-md cursor-pointer">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600 text-white">
                                                    <x-heroicon-o-check-circle class="h-5 w-5" />
                                                </div>
                                                <p class="font-bold text-gray-900">Default Address</p>
                                            </div>
                                            <p class="mt-3 text-sm text-gray-700 font-medium">{{ optional($profile->primary_address)->street ?? optional($profile->address)->street ?? 'No default address set' }}</p>
                                            <p class="mt-1 text-xs text-gray-600">{{ optional($profile->primary_address)->city ? (optional($profile->primary_address)->city . ', ' . optional($profile->primary_address)->country) : (optional($profile->address)->city ? (optional($profile->address)->city . ', ' . optional($profile->address)->country) : '') }}</p>
                                        </div>
                                        <button type="button" class="text-gray-400 transition-all duration-200 group-hover:text-green-600 opacity-0 group-hover:opacity-100">
                                            <x-heroicon-o-pencil class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>

                                <div class="group rounded-2xl border-2 border-gray-200 bg-white p-5 transition-all duration-200 hover:border-gray-300 hover:shadow-md cursor-pointer">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-200 text-gray-600">
                                                    <x-heroicon-o-map-pin class="h-5 w-5" />
                                                </div>
                                                <p class="font-bold text-gray-900">Office Address</p>
                                            </div>
                                            <p class="mt-3 text-sm text-gray-700 font-medium">123 Business Park, Downtown</p>
                                            <p class="mt-1 text-xs text-gray-600">Metro Manila, Philippines</p>
                                        </div>
                                        <button type="button" class="text-gray-400 transition-all duration-200 group-hover:text-gray-600 opacity-0 group-hover:opacity-100">
                                            <x-heroicon-o-pencil class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Methods --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="mb-8 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900">Payment Methods</h3>
                                <a href="/address-payment/add?tab=payment" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300 hover:shadow-md">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                    Add Card
                                </a>
                            </div>

                            <div class="space-y-3">
                                <div class="group rounded-2xl border-2 border-green-200 bg-gradient-to-br from-blue-600 to-blue-700 p-5 shadow-lg transition-all duration-200 hover:shadow-xl cursor-pointer overflow-hidden relative">
                                    <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
                                    <div class="flex items-center justify-between relative z-10">
                                        <div class="text-white">
                                            <p class="text-xs font-semibold opacity-90">VISA</p>
                                            <p class="mt-6 text-lg font-bold tracking-widest">•••• •••• •••• 4242</p>
                                            <div class="mt-4 flex justify-between items-end">
                                                <div>
                                                    <p class="text-xs opacity-75">Card Holder</p>
                                                    <p class="text-sm font-semibold">JUAN DELA CRUZ</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs opacity-75">Expires</p>
                                                    <p class="text-sm font-semibold">12/25</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">Default</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="group rounded-2xl border-2 border-gray-200 bg-gradient-to-br from-red-600 to-red-700 p-5 shadow-lg transition-all duration-200 hover:shadow-xl cursor-pointer overflow-hidden relative">
                                    <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
                                    <div class="flex items-center justify-between relative z-10">
                                        <div class="text-white">
                                            <p class="text-xs font-semibold opacity-90">MASTERCARD</p>
                                            <p class="mt-6 text-lg font-bold tracking-widest">•••• •••• •••• 8888</p>
                                            <div class="mt-4 flex justify-between items-end">
                                                <div>
                                                    <p class="text-xs opacity-75">Card Holder</p>
                                                    <p class="text-sm font-semibold">JUAN DELA CRUZ</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs opacity-75">Expires</p>
                                                    <p class="text-sm font-semibold">08/26</p>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="text-white transition-all duration-200 opacity-0 group-hover:opacity-100">
                                            <x-heroicon-o-trash class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload Photo Modal --}}
                <div 
                    x-show="showPhotoModal" 
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/10"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    <div 
                        @click.away="showPhotoModal = false"
                        class="relative w-full max-w-md transform rounded-3xl bg-white shadow-xl transition-all duration-300"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                    >
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between border-b border-gray-200 px-8 py-6">
                            <h2 class="text-xl font-bold text-gray-900">Upload Profile Photo</h2>
                            <button 
                                @click="showPhotoModal = false"
                                type="button" 
                                class="text-gray-400 transition-colors duration-200 hover:text-gray-600"
                            >
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-8 py-6">
                            <form class="space-y-6">
                                {{-- Upload Area --}}
                                <div 
                                    class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-gray-100 p-8 transition-all duration-200 hover:border-green-400 hover:bg-green-50 cursor-pointer"
                                    @dragover.prevent="$el.classList.add('border-green-500', 'bg-green-50')"
                                    @dragleave.prevent="$el.classList.remove('border-green-500', 'bg-green-50')"
                                    @drop.prevent="$el.classList.remove('border-green-500', 'bg-green-50')"
                                >
                                    <x-heroicon-o-arrow-up-tray class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-sm font-semibold text-gray-900">Click to upload or drag and drop</p>
                                    <p class="mt-1 text-xs text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                    <input type="file" class="hidden" accept="image/*" />
                                </div>

                                {{-- File Info --}}
                                <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
                                    <div class="flex gap-3">
                                        <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                            <x-heroicon-o-information-circle class="h-4 w-4" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900">Best for profile photos</p>
                                            <p class="mt-1 text-xs text-blue-700">Use clear, square images for best results. Recommended size: 400x400px</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Preview (if file selected) --}}
                                <div class="relative">
                                    <div class="flex h-32 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50">
                                        <div class="text-center">
                                            <x-heroicon-o-photo class="mx-auto h-8 w-8 text-gray-400" />
                                            <p class="mt-2 text-sm text-gray-600">No image selected</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Form Actions --}}
                                <div class="flex gap-3 pt-4">
                                    <button 
                                        @click="showPhotoModal = false"
                                        type="button" 
                                        class="flex-1 rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        class="flex-1 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-4 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md"
                                    >
                                        Upload Photo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
