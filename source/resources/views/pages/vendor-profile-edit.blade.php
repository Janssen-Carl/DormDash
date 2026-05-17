@extends('layouts.main-vendor')

@section('title', 'Edit Profile')

@section('content')
            <div class="mx-auto max-w-2xl px-8 py-12">
                <div class="mb-8">
                    <a href="/vendor-profile" class="inline-flex items-center gap-2 text-green-600 font-semibold text-sm hover:text-green-700 transition-colors mb-4">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        Back to Profile
                    </a>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Edit Profile</h1>
                    <p class="mt-2 text-gray-600">Update your personal information</p>
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
                        <a href="/vendor-profile" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        @endsection
