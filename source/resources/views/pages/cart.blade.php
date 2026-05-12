<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>DormDash - Cart</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    </head>

    <body>
        @extends('layouts.main')

        @section('content')
            <div class="mx-auto max-w-7xl px-8 py-8">
                <h1 class="mb-8 text-4xl font-bold tracking-tight text-gray-900">Your Shopping Cart</h1>

                <div class="grid grid-cols-3 gap-8">
                    {{-- Cart Items --}}
                    <div class="col-span-2">
                        <div class="rounded-xl border border-gray-200 bg-white">
                            @foreach (range(1, 3) as $i)
                                <div class="flex items-center gap-4 border-b border-gray-200 p-6 last:border-b-0">
                                    <div class="h-20 w-20 rounded-lg bg-gray-200">
                                        <div class="flex h-full w-full items-center justify-center text-gray-400">
                                            <x-heroicon-o-photo class="h-8 w-8" />
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ ['Fresh Apples', 'Whole Milk', 'Chicken Breast'][$i - 1] }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ ['₱40.00/pc', '₱120.00/pc', '₱300.00/kg'][$i - 1] }}</p>
                                    </div>

                                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-2">
                                        <button type="button" class="text-gray-500 transition-colors hover:text-gray-700">
                                            <x-heroicon-o-minus class="h-4 w-4" />
                                        </button>
                                        <span class="w-8 text-center text-sm font-medium">{{ $i }}</span>
                                        <button type="button" class="text-gray-500 transition-colors hover:text-gray-700">
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                        </button>
                                    </div>

                                    <span class="w-20 text-right font-semibold text-gray-900">₱{{ 40 * $i }}.00</span>

                                    <button type="button" class="text-gray-400 transition-colors hover:text-red-500">
                                        <x-heroicon-o-trash class="h-5 w-5" />
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <a href="/products" class="font-semibold text-green-600 transition-colors hover:text-green-700">
                                ← Continue Shopping
                            </a>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <aside class="col-span-1">
                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Order Summary</h2>

                            <div class="space-y-3 border-b border-gray-200 pb-4">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <span>₱300.00</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Delivery Fee</span>
                                    <span>₱50.00</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Tax</span>
                                    <span>₱28.00</span>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span>₱378.00</span>
                            </div>

                            <button type="button" class="mt-6 w-full rounded-lg bg-green-600 py-3 font-semibold text-white transition-colors hover:bg-green-700">
                                Proceed to Checkout
                            </button>

                            <button type="button" class="mt-3 w-full rounded-lg border border-gray-200 py-3 text-sm font-semibold text-gray-900 transition-colors hover:bg-gray-50">
                                Apply Promo Code
                            </button>
                        </div>
                    </aside>
                </div>
            </div>
        @endsection

        @livewireScripts
        @fluxScripts
    </body>
</html>
