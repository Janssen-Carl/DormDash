<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>DormDash - Add Address & Payment</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    </head>

    <body>
        @extends('layouts.main')

        @section('content')
            <div class="mx-auto max-w-3xl px-8 py-12" x-data="{ activeTab: 'address' }">
                <div class="mb-8">
                    <a href="/profile" class="inline-flex items-center gap-2 text-green-600 font-semibold text-sm hover:text-green-700 transition-colors mb-4">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        Back to Profile
                    </a>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Add New Details</h1>
                    <p class="mt-2 text-gray-600">Add a new address or payment method to your account</p>
                </div>

                {{-- Tabs --}}
                <div class="mb-8 flex gap-4 border-b border-gray-200">
                    <button
                        @click="activeTab = 'address'"
                        :class="{ 'border-b-2 border-green-600 text-green-600': activeTab === 'address', 'border-b-2 border-transparent text-gray-600 hover:text-gray-900': activeTab !== 'address' }"
                        class="pb-4 font-semibold transition-colors duration-200"
                    >
                        <x-heroicon-o-map-pin class="inline h-5 w-5 mr-2" />
                        New Address
                    </button>
                    <button
                        @click="activeTab = 'payment'"
                        :class="{ 'border-b-2 border-green-600 text-green-600': activeTab === 'payment', 'border-b-2 border-transparent text-gray-600 hover:text-gray-900': activeTab !== 'payment' }"
                        class="pb-4 font-semibold transition-colors duration-200"
                    >
                        <x-heroicon-o-credit-card class="inline h-5 w-5 mr-2" />
                        New Payment
                    </button>
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
                        <a href="/profile" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Add Address
                        </button>
                    </div>
                </form>

                {{-- Payment Tab --}}
                <form x-show="activeTab === 'payment'" class="space-y-6">
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Payment Card Details</h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Cardholder Name</label>
                                <input
                                    type="text"
                                    placeholder="e.g., Juan Dela Cruz"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none uppercase"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Card Number</label>
                                <input
                                    type="text"
                                    placeholder="1234 5678 9012 3456"
                                    maxlength="19"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none font-mono"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Expiry Date</label>
                                    <input
                                        type="text"
                                        placeholder="MM/YY"
                                        maxlength="5"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none font-mono"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">CVV</label>
                                    <input
                                        type="text"
                                        placeholder="123"
                                        maxlength="3"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none font-mono"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Card Type</label>
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 transition-all duration-200">
                                        <input type="radio" name="card_type" value="visa" checked class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-600" />
                                        <span class="text-sm font-medium text-gray-700">Visa</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-red-300 transition-all duration-200">
                                        <input type="radio" name="card_type" value="mastercard" class="rounded-full border-gray-300 text-red-600 focus:ring-red-600" />
                                        <span class="text-sm font-medium text-gray-700">Mastercard</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-orange-300 transition-all duration-200">
                                        <input type="radio" name="card_type" value="amex" class="rounded-full border-gray-300 text-orange-600 focus:ring-orange-600" />
                                        <span class="text-sm font-medium text-gray-700">Amex</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-purple-300 transition-all duration-200">
                                        <input type="radio" name="card_type" value="discover" class="rounded-full border-gray-300 text-purple-600 focus:ring-purple-600" />
                                        <span class="text-sm font-medium text-gray-700">Discover</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 border-green-200 bg-green-50 hover:bg-green-100 transition-all duration-200">
                                    <input type="checkbox" checked class="rounded border-green-300 text-green-600 focus:ring-green-600" />
                                    <span class="font-semibold text-gray-700">Set as default payment method</span>
                                </label>
                            </div>

                            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                                <div class="flex gap-3">
                                    <x-heroicon-o-shield-check class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p class="font-semibold text-blue-900">Your payment is secure</p>
                                        <p class="text-sm text-blue-700 mt-1">We use industry-standard encryption to protect your payment information.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <a href="/profile" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Add Payment Method
                        </button>
                    </div>
                </form>
            </div>
        @endsection

        @livewireScripts
        @fluxScripts
    </body>
</html>
