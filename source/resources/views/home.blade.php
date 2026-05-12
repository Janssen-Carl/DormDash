<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <title>Home - DormDash</title>
</head>

<body>
    @extends('layouts.main')

    @section('content')
        {{-- Welcome Section --}}
        <section class="px-8 py-16">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-2 gap-16 items-center">
                    <div class="flex flex-col justify-center">
                        <h1 class="text-5xl font-bold tracking-tight text-gray-900">Welcome to DormDash!</h1>
                        <p class="mt-4 text-lg text-gray-600">Your one-stop shop for all your grocery needs with amazing
                            deals.</p>

                        <div class="mt-8">
                            <div class="relative mb-6">
                                <x-heroicon-o-magnifying-glass class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" />
                                <input type="text" placeholder="Search for groceries..."
                                    class="w-full rounded-lg border border-gray-200 bg-white py-3 pl-12 pr-4 text-gray-900 placeholder-gray-500 transition-colors focus:border-green-600 focus:ring-1 focus:ring-green-600" />
                            </div>
                        </div>

                        <div class="flex gap-3 w-full">
                            <a href="/products/offers"
                                class="flex flex-1 h-12 items-center justify-center rounded-lg border border-green-600 bg-white font-semibold text-green-600 transition-colors hover:bg-green-50">
                                See Deals
                            </a>

                            <a href="/products"
                                class="flex flex-1 h-12 items-center justify-center rounded-lg bg-green-600 font-semibold text-white transition-colors hover:bg-green-700">
                                Browse Products
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="h-80 w-full rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200">
                            <div class="flex h-full w-full items-center justify-center text-gray-300">
                                <x-heroicon-o-photo class="h-24 w-24" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Featured Products Section --}}
        <section class="px-8 py-16 bg-white">
            <div class="mx-auto max-w-6xl">
                <div class="mb-12 text-center">
                    <h2 class="text-4xl font-bold tracking-tight text-gray-900">Featured Products</h2>
                    <p class="mt-2 text-gray-600">Explore our top-selling items!</p>
                </div>

                <div class="grid grid-cols-4 gap-6">
                    @foreach (range(1, 4) as $i)
                        <div
                            class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
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
                                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Fresh Product</p>
                                <h3 class="mt-2 text-base font-bold text-gray-900">
                                    {{ ['Apples', 'Bread Loaf', 'Almond Milk', 'Chicken Breast'][$i - 1] }}</h3>

                                <div class="mt-2 flex items-center gap-1">
                                    @for ($j = 0; $j < 5; $j++)
                                        <x-heroicon-s-star class="h-3.5 w-3.5 {{ $j < 4 ? 'text-yellow-400' : 'text-gray-300' }}" />
                                    @endfor
                                    <span class="ml-1 text-xs text-gray-500">({{ 40 + $i * 5 }})</span>
                                </div>

                                <p class="mt-2 text-xs text-gray-600">Stock: {{ 40 + $i * 10 }} available</p>

                                <div class="mt-4 flex items-baseline gap-2">
                                    <span class="text-xl font-bold text-gray-900">₱{{ 40 + $i * 30 }}</span>
                                    <span class="text-xs text-gray-500">/{{ ['pc', 'loaf', 'bottle', 'kg'][$i - 1] }}</span>
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
            </div>
        </section>

        {{-- Today's Exclusive Offers Section --}}
        <section class="px-8 py-16 bg-gray-50">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-4 gap-8">
                    {{-- Left Sidebar with Offers List --}}
                    <div class="col-span-1">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-2">Today's</h2>
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-6">Exclusive Offers</h2>
                        <p class="text-sm text-gray-600 mb-8">Don't miss out on these limited-time deals!</p>

                        <div class="space-y-3">
                            <a href="/products/offers"
                                class="flex items-center gap-2 text-green-600 font-semibold text-sm hover:text-green-700 transition-colors">
                                <x-heroicon-o-arrow-right class="h-4 w-4" />
                                See All Offers
                            </a>
                            <a href="/products"
                                class="inline-flex h-10 items-center justify-center rounded-lg bg-green-600 px-4 text-xs font-semibold text-white transition-colors hover:bg-green-700 w-full">
                                Grab Deal
                            </a>
                        </div>
                    </div>

                    {{-- Right Grid with Offer Cards --}}
                    <div class="col-span-3">
                        <div class="grid grid-cols-3 gap-6">
                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-emerald-50 to-green-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">💰</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Buy 1 Get 1 Free</h3>
                                    <p class="mt-1 text-xs text-gray-600">Beverages</p>
                                    <p class="mt-3 text-sm font-bold text-green-600">Limited Time Only</p>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">📦</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Combo Deal</h3>
                                    <p class="mt-1 text-xs text-gray-600">Snacks</p>
                                    <p class="mt-3 text-sm font-bold text-blue-600">Save up to 30%</p>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-orange-50 to-yellow-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">🍰</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Bakery Items</h3>
                                    <p class="mt-1 text-xs text-gray-600">Freshly Baked</p>
                                    <p class="mt-3 text-sm font-bold text-orange-600">Buy 2, Get 1 Free</p>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-purple-50 to-pink-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">✨</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Weekly Discount</h3>
                                    <p class="mt-1 text-xs text-gray-600">Essential Groceries</p>
                                    <p class="mt-3 text-sm font-bold text-purple-600">Up to 15% Off</p>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-rose-50 to-red-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">🎁</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Special Promo</h3>
                                    <p class="mt-1 text-xs text-gray-600">Dairy Products</p>
                                    <p class="mt-3 text-sm font-bold text-red-600">Free Delivery</p>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                <div
                                    class="flex flex-col items-center justify-center bg-gradient-to-br from-teal-50 to-cyan-50 px-6 py-8 h-48">
                                    <div class="text-6xl group-hover:scale-110 transition-transform duration-300">🛒</div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">Flash Sale</h3>
                                    <p class="mt-1 text-xs text-gray-600">Selected Items</p>
                                    <p class="mt-3 text-sm font-bold text-teal-600">Hurry, Limited Stock</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @livewireScripts
    @fluxScripts
</body>

</html>