@extends('layouts.main')

@section('title', 'Add Address & Payment')

@section('content')
            <div class="mx-auto max-w-3xl px-8 py-12" x-data="{ activeTab: 'address' }">
                <div class="mb-8">
                    <a href="/vendor-profile" class="inline-flex items-center gap-2 text-green-600 font-semibold text-sm hover:text-green-700 transition-colors mb-4">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        Back to Profile
                    </a>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Add New Details</h1>
                    <p class="mt-2 text-gray-600">Add a new address or payment method to your account</p>
                </div>

               

                {{-- Address Tab --}}
                <form x-show="activeTab === 'address'" class="space-y-6">
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Delivery Address Details</h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Full Address</label>
                                <input
                                    type="text"
                                    placeholder="e.g., Room 123, Dormitory A, University Campus"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">City</label>
                                    <input
                                        type="text"
                                        placeholder="e.g., Metro Manila"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Country</label>
                                    <input
                                        type="text"
                                        placeholder="e.g., Philippines"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Postal Code</label>
                                <input
                                    type="text"
                                    placeholder="e.g., 1000"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Address Type</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-green-300 transition-all duration-200">
                                        <input type="radio" name="address_type" value="home" checked class="rounded-full border-gray-300 text-green-600 focus:ring-green-600" />
                                        <span class="font-medium text-gray-700">Home</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-green-300 transition-all duration-200">
                                        <input type="radio" name="address_type" value="office" class="rounded-full border-gray-300 text-green-600 focus:ring-green-600" />
                                        <span class="font-medium text-gray-700">Office</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-green-300 transition-all duration-200">
                                        <input type="radio" name="address_type" value="other" class="rounded-full border-gray-300 text-green-600 focus:ring-green-600" />
                                        <span class="font-medium text-gray-700">Other</span>
                                    </label>
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

                    <div class="flex gap-3 pt-4">
                        <a href="/vendor-profile" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Add Address
                        </button>
                    </div>
                </form>
            </div>
        @endsection
