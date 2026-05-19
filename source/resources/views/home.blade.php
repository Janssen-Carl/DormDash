@extends('layouts.main')

@section('title', 'Home - DormDash')

@section('content')
        <section class="px-8 py-16 lg:py-24 overflow-hidden relative bg-white">
            <!-- Background Decorative Blob -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            
            <div class="mx-auto max-w-6xl relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    
                    {{-- Left Content --}}
                    <div class="flex flex-col justify-center">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-100 text-green-700 text-sm font-semibold w-fit mb-6 shadow-sm">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            Lightning Fast Delivery to Your Dorm
                        </div>
                        
                        <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-[1.15]">
                            Craving a snack? <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-400">We've got you.</span>
                        </h1>
                        
                        <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-lg">
                            Your ultimate one-stop shop for late-night cravings, essential groceries, and amazing student deals. Delivered straight to your door.
                        </p>

                        <form action="/products" method="GET" class="mt-8 relative max-w-md group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="search" name="q" placeholder="Search for groceries, chips, drinks..."
                                class="w-full rounded-2xl border-0 bg-white py-4 pl-12 pr-16 text-gray-900 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all focus:ring-2 focus:ring-green-500 focus:shadow-[0_8px_30px_rgb(22,163,74,0.15)] placeholder-gray-400 font-medium" />
                            <button type="submit"
                                class="absolute right-2 top-2 bottom-2 flex w-12 items-center justify-center rounded-xl bg-green-600 text-white transition-all hover:bg-green-700 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg"
                                aria-label="Search products">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                            </button>
                        </form>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <a href="/products"
                                class="inline-flex h-12 items-center justify-center rounded-xl bg-gray-900 px-8 font-semibold text-white transition-all hover:bg-gray-800 hover:shadow-lg hover:-translate-y-0.5">
                                Browse Products
                            </a>
                            <a href="/products/offers"
                                class="inline-flex h-12 items-center justify-center rounded-xl border-2 border-gray-200 bg-white px-8 font-semibold text-gray-700 transition-all hover:border-gray-300 hover:bg-gray-50 hover:-translate-y-0.5">
                                See Deals
                            </a>
                        </div>
                    </div>

                    {{-- Right Content: Image --}}
                    <div class="relative mt-8 lg:mt-0 flex justify-center lg:justify-end">
                        <!-- Decorative background element -->
                        <div class="absolute inset-4 bg-gradient-to-tr from-green-100 to-emerald-50 rounded-[3rem] rotate-3 scale-105 -z-10 transition-transform hover:rotate-6 duration-500"></div>
                        
                        <!-- Hero Image -->
                        <img src="{{ asset('images/home_hero_groceries.png') }}" alt="Fresh groceries and snacks" class="w-full max-w-[500px] rounded-[2.5rem] shadow-2xl object-cover aspect-square ring-1 ring-black/5 hover:-translate-y-2 transition-transform duration-500 bg-white" />
                        
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-4 -left-4 md:-left-8 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4 transition-transform hover:-translate-y-1 z-20">
                            <div class="bg-yellow-50 p-2.5 rounded-xl">
                                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Top Rated</p>
                                <p class="text-xs text-gray-500">Loved by Students</p>
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
