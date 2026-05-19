@extends('layouts.main')

@section('title', 'Home - DormDash')

@section('content')
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
                    @forelse ($featuredProducts as $product)
                        <div
                            class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
                            <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                @if ($product->images->first())
                                    <img src="{{ asset($product->images->first()->image) }}"
                                         alt="{{ $product->name }}"
                                         class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center text-gray-300 group-hover:scale-110 transition-transform duration-300">
                                        <x-heroicon-o-photo class="h-16 w-16" />
                                    </div>
                                @endif

                                @if ($product->is_perishable)
                                    <div
                                        class="absolute top-3 right-3 bg-green-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                        Fresh
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">
                                    {{ $product->vendor->name ?? 'DormDash' }}
                                </p>
                                <h3 class="mt-2 text-base font-bold text-gray-900">{{ $product->name }}</h3>

                                <p class="mt-2 text-xs text-gray-600">Stock: {{ $product->stock }} available</p>

                                <div class="mt-4 flex items-baseline gap-2">
                                    <span class="text-xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                    @if ($product->unit_type)
                                        <span class="text-xs text-gray-500">/{{ $product->unit_type }}</span>
                                    @endif
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
                    @empty
                        <div class="col-span-4 text-center py-12 text-gray-400">
                            <x-heroicon-o-inbox class="h-16 w-16 mx-auto mb-4" />
                            <p class="text-lg font-medium">No products available yet.</p>
                        </div>
                    @endforelse
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
                            @php
                                $offerGradients = [
                                    'from-emerald-50 to-green-50',
                                    'from-blue-50 to-indigo-50',
                                    'from-orange-50 to-yellow-50',
                                    'from-purple-50 to-pink-50',
                                    'from-rose-50 to-red-50',
                                    'from-teal-50 to-cyan-50',
                                ];
                                $offerColors = [
                                    'text-green-600',
                                    'text-blue-600',
                                    'text-orange-600',
                                    'text-purple-600',
                                    'text-red-600',
                                    'text-teal-600',
                                ];
                                $offerEmojis = ['💰', '📦', '🍰', '✨', '🎁', '🛒'];
                            @endphp

                            @forelse ($offers as $index => $offer)
                                <div
                                    class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                    <div
                                        class="flex flex-col items-center justify-center bg-gradient-to-br {{ $offerGradients[$index % count($offerGradients)] }} px-6 py-8 h-48">
                                        <div class="text-6xl group-hover:scale-110 transition-transform duration-300">
                                            {{ $offerEmojis[$index % count($offerEmojis)] }}
                                        </div>
                                        <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $offer->name }}</h3>
                                        <p class="mt-1 text-xs text-gray-600">{{ $offer->item->name }}</p>
                                        <p class="mt-3 text-sm font-bold {{ $offerColors[$index % count($offerColors)] }}">
                                            @if ($offer->type === 'percentage')
                                                {{ number_format($offer->value) }}% Off
                                            @else
                                                ₱{{ number_format($offer->value, 2) }} Off
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @empty
                                {{-- Fallback static cards when no offers exist --}}
                                <div
                                    class="overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
                                    <div
                                        class="flex flex-col items-center justify-center bg-gradient-to-br from-emerald-50 to-green-50 px-6 py-8 h-48">
                                        <div class="text-6xl group-hover:scale-110 transition-transform duration-300">💰</div>
                                        <h3 class="mt-4 text-lg font-bold text-gray-900">Coming Soon</h3>
                                        <p class="mt-1 text-xs text-gray-600">Stay tuned for deals</p>
                                        <p class="mt-3 text-sm font-bold text-green-600">Check Back Later</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection