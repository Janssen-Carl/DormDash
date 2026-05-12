<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>DormDash - Orders</title>

        @vite(['resources/js/app.js', 'resources/css/app.css'])

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    </head>

    <body>
        @extends('layouts.main')

        @section('content')
            <div class="mx-auto max-w-6xl px-8 py-12">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Your Orders</h1>
                    <p class="mt-2 text-gray-600">Track and manage your orders</p>
                </div>

                <div class="space-y-6">
                    @foreach (range(1, 4) as $i)
                        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-md hover:border-gray-200">
                            <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">Order #{{ 10000 + $i }}</h3>
                                            <p class="mt-1 text-sm text-gray-500">Placed on {{ now()->subDays($i)->format('M d, Y') }}</p>
                                        </div>

                                        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold
                                            {{ ($i - 1) % 4 === 0 ? 'bg-green-100 text-green-700' : '' }}
                                            {{ ($i - 1) % 4 === 1 ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ ($i - 1) % 4 === 2 ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ ($i - 1) % 4 === 3 ? 'bg-gray-100 text-gray-700' : '' }}">
                                            <span class="h-2 w-2 rounded-full
                                                {{ ($i - 1) % 4 === 0 ? 'bg-green-600' : '' }}
                                                {{ ($i - 1) % 4 === 1 ? 'bg-blue-600' : '' }}
                                                {{ ($i - 1) % 4 === 2 ? 'bg-amber-600' : '' }}
                                                {{ ($i - 1) % 4 === 3 ? 'bg-gray-600' : '' }}"></span>
                                            <span>{{ ['Delivered', 'In Transit', 'Processing', 'Cancelled'][($i - 1) % 4] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 px-6 py-6">
                                <div class="space-y-4">
                                    @foreach (range(1, 2) as $j)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                    <x-heroicon-o-photo class="h-8 w-8 text-gray-400" />
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900">{{ ['Fresh Apples', 'Whole Milk', 'Chicken Breast', 'Bread Loaf'][$j - 1] }}</p>
                                                    <p class="text-sm text-gray-500">Quantity: {{ $j }}</p>
                                                </div>
                                            </div>
                                            <span class="font-semibold text-gray-900">₱{{ 40 * $j }}.00</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t border-gray-100 bg-gray-50 px-6 py-6">
                                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Order Total</p>
                                        <p class="text-2xl font-bold text-gray-900">₱{{ 80 * $i }}.00</p>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-100 hover:border-gray-300">
                                            <x-heroicon-o-arrow-path class="h-4 w-4" />
                                            Track Order
                                        </button>

                                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300">
                                            <x-heroicon-o-arrow-path class="h-4 w-4" />
                                            Reorder
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count(range(1, 4)) === 0)
                        <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center">
                            <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-gray-600">Start shopping to create your first order!</p>
                            <a href="/products" class="mt-6 inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                                Browse Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endsection

        @livewireScripts
        @fluxScripts
    </body>
</html>
