@extends('layouts.main')

@section('title', 'Products')

@section('content')
        <div x-data="{ sidebarOpen: true }" class="relative flex min-h-[calc(100vh-80px)]">

            {{-- Sidebar Filters --}}
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full" class="relative z-40 w-72 overflow-y-auto bg-white">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        <button @click="sidebarOpen = false" type="button"
                            class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>
                    </div>

                    {{-- All Products --}}
                    <div class="mb-6 cursor-pointer">
                        <a href="/products"
                            class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                            <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                            <span>All Products</span>
                        </a>
                    </div>

                    {{-- Vendor --}}
                    <div class="mb-6">
                        <button type="button"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                            <x-heroicon-o-building-storefront class="h-4 w-4" />
                            <span>Vendor</span>
                        </button>
                    </div>

                    {{-- Categories --}}
                    <div class="mb-6">
                        <button type="button"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                            <x-heroicon-o-tag class="h-4 w-4" />
                            <span>Categories</span>
                        </button>
                    </div>

                    {{-- Bundles --}}
                    <div class="mb-6">
                        <button type="button"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                            <x-heroicon-o-shopping-bag class="h-4 w-4" />
                            <span>Bundles</span>
                        </button>
                    </div>

                    {{-- Discounts --}}
                    <div>
                        <button type="button"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                            <x-heroicon-o-sparkles class="h-4 w-4" />
                            <span>Discounts</span>
                        </button>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto px-8 py-8">
                {{-- Toggle Button --}}
                <div x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="mb-8 flex items-center justify-between">
                    <button @click="sidebarOpen = true" type="button"
                        class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                        <x-heroicon-o-bars-3 class="h-6 w-6" />
                    </button>
                </div>

                {{-- Header Section --}}
                <div class="py-8 mb-12 flex flex-col items-center">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Products</h1>
                        <p class="mt-2 text-gray-500">Explore our most popular items this week!</p>
                    </div>

                    <div class="mt-6 grid w-full max-w-sm grid-cols-2 gap-3">
                        <a href="/products/offers"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-green-600 bg-white text-sm font-semibold text-green-600 transition-colors hover:bg-green-50">
                            Shop Offers
                        </a>

                        <a href="/products"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-green-600 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                            View All Products
                        </a>
                    </div>
                </div>

                {{-- Products by Category --}}

                {{-- Fruits Section --}}
                <section class="mb-12" x-data="productCarousel()">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">Fruits</h2>
                        <div class="flex gap-2">
                            <button @click="scrollCarousel('fruits-carousel', -400)" type="button"
                                class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                <x-heroicon-o-chevron-left class="h-5 w-5" />
                            </button>
                            <button @click="scrollCarousel('fruits-carousel', 400)" type="button"
                                class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                <x-heroicon-o-chevron-right class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div id="fruits-carousel" class="scrollbar-hide flex gap-4 overflow-x-auto transition-all duration-300"
                        style="scroll-behavior: smooth;">
                        @foreach (range(1, 8) as $i)
                            <div
                                class="group shrink-0 w-72 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
                                <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    <div
                                        class="flex h-full w-full items-center justify-center text-gray-300 group-hover:scale-110 transition-transform duration-300">
                                        <x-heroicon-o-photo class="h-16 w-16" />
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-green-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                        Fresh
                                    </div>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Fresh Fruits</p>
                                    <h3 class="mt-2 text-base font-bold text-gray-900">
                                        {{ ['Apples', 'Oranges', 'Bananas', 'Mangoes', 'Grapes', 'Pineapple', 'Strawberries', 'Blueberries'][$i - 1] }}
                                    </h3>

                                    <div class="mt-2 flex items-center gap-1">
                                        @for ($j = 0; $j < 5; $j++)
                                            <x-heroicon-s-star
                                                class="h-3.5 w-3.5 {{ $j < 4 ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                        <span class="ml-1 text-xs text-gray-500">({{ 40 + $i * 5 }})</span>
                                    </div>

                                    <p class="mt-2 text-xs text-gray-600">Stock: {{ 30 + $i * 10 }} available</p>

                                    <div class="mt-4 flex items-baseline gap-2">
                                        <span class="text-xl font-bold text-gray-900">₱{{ 40 + $i * 10 }}</span>
                                        <span class="text-xs text-gray-500">/pc</span>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <button type="button"
                                            class="flex-1 rounded-lg bg-green-50 border border-green-200 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100">
                                            <x-heroicon-o-shopping-cart class="inline h-4 w-4 mr-1" />
                                            Add to Cart
                                        </button>
                                        <button type="button"
                                            class="flex-1 rounded-lg bg-green-600 py-2 text-xs font-semibold text-white transition-all duration-200 hover:bg-green-700 shadow-sm hover:shadow-md">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Vegetables Section --}}
                <section x-data="productCarousel()">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">Vegetables</h2>
                        <div class="flex gap-2">
                            <button @click="scrollCarousel('vegetables-carousel', -400)" type="button"
                                class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                <x-heroicon-o-chevron-left class="h-5 w-5" />
                            </button>
                            <button @click="scrollCarousel('vegetables-carousel', 400)" type="button"
                                class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                <x-heroicon-o-chevron-right class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div id="vegetables-carousel"
                        class="scrollbar-hide flex gap-4 overflow-x-auto transition-all duration-300"
                        style="scroll-behavior: smooth;">
                        @foreach (range(1, 8) as $i)
                            <div
                                class="group shrink-0 w-72 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
                                <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    <div
                                        class="flex h-full w-full items-center justify-center text-gray-300 group-hover:scale-110 transition-transform duration-300">
                                        <x-heroicon-o-photo class="h-16 w-16" />
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-green-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                        Fresh
                                    </div>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Fresh Produce</p>
                                    <h3 class="mt-2 text-base font-bold text-gray-900">
                                        {{ ['Carrot', 'Broccoli', 'Lettuce', 'Tomato', 'Bell Pepper', 'Cucumber', 'Spinach', 'Cabbage'][$i - 1] }}
                                    </h3>

                                    <div class="mt-2 flex items-center gap-1">
                                        @for ($j = 0; $j < 5; $j++)
                                            <x-heroicon-s-star
                                                class="h-3.5 w-3.5 {{ $j < 4 ? 'text-yellow-400' : 'text-gray-300' }}" />
                                        @endfor
                                        <span class="ml-1 text-xs text-gray-500">({{ 35 + $i * 4 }})</span>
                                    </div>

                                    <p class="mt-2 text-xs text-gray-600">Stock: {{ 100 - $i * 10 }} available</p>

                                    <div class="mt-4 flex items-baseline gap-2">
                                        <span class="text-xl font-bold text-gray-900">₱{{ 25 + $i * 15 }}</span>
                                        <span class="text-xs text-gray-500">/kg</span>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <button type="button"
                                            class="flex-1 rounded-lg bg-green-50 border border-green-200 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100">
                                            <x-heroicon-o-shopping-cart class="inline h-4 w-4 mr-1" />
                                            Add to Cart
                                        </button>
                                        <button type="button"
                                            class="flex-1 rounded-lg bg-green-600 py-2 text-xs font-semibold text-white transition-all duration-200 hover:bg-green-700 shadow-sm hover:shadow-md">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </main>
        </div>
    @endsection

    <script>
        function productCarousel() {
            return {
                scrollCarousel(carouselId, distance) {
                    const carousel = document.getElementById(carouselId);
                    if (carousel) {
                        carousel.scrollLeft += distance;
                    }
                }
            };
        }
    </script>

    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>