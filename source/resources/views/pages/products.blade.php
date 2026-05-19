@extends('layouts.main')

@section('title', 'Products')

@section('content')
        <div x-data="{ sidebarOpen: true }" class="relative flex min-h-[calc(100vh-80px)]">

        {{-- Sidebar Filters --}}
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative z-40 w-72 shrink-0 overflow-y-auto bg-white border-r border-gray-100">
            <form action="/products" method="GET" class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    <div class="flex items-center gap-3">
                        @if (request()->hasAny(['vendors', 'categories']))
                            <a href="/products"
                                class="text-xs font-semibold text-red-600 hover:text-red-700 transition-colors">Clear
                                All</a>
                        @endif
                        <button @click="sidebarOpen = false" type="button"
                                 class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="h-5 w-5" />
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
                </form>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto px-8 py-8 w-full">
                @if(session('success'))
                    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-800 shadow-xl border border-green-300" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
                        <x-heroicon-s-check-circle class="h-5 w-5 text-green-600" />
                        {{ session('success') }}
                    </div>
                @endif
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

                {{-- Discounts --}}
                <div>
                    <button type="button"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-100">
                        <x-heroicon-o-sparkles class="h-4 w-4" />
                        <span>Discounts</span>
                    </button>
                </div>
            </form>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto px-8 py-8 w-full">
            @if (session('success'))
                <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-800 shadow-xl border border-green-300"
                    x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
                    <x-heroicon-s-check-circle class="h-5 w-5 text-green-600" />
                    {{ session('success') }}
                </div>
            @endif
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
            @guest
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
            @endguest
            
            {{-- Search Bar --}}
            <div class="mt-8">
                <div class="relative mb-6">
                    <x-heroicon-o-magnifying-glass class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" />
                    <input type="text" name="search" placeholder="Search for items..."
                        class="w-full rounded-lg border border-gray-200 bg-white py-3 pl-12 pr-4 text-gray-900 placeholder-gray-500 transition-colors focus:border-green-600 focus:ring-1 focus:ring-green-600"
                        value="{{ request('search') }}" />
                    <button type="submit" class="absolute right-4 top-3.5 text-gray-500 hover:text-gray-700">
                        <x-heroicon-o-arrow-right class="h-5 w-5" />
                    </button>
                </div>
            </div>

                        <a href="/products"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-green-600 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                            View All Products
                        </a>
                    </div>
                </div>
                @endguest

                {{-- Products by Category --}}
                @forelse ($categoryItems as $group)
                    <section class="mb-12" x-data="productCarousel()">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $group['category']->name }}</h2>
                            <div class="flex gap-2">
                                <button @click="scrollCarousel('carousel-{{ $group['category']->category_id }}', -400)" type="button"
                                    class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                    <x-heroicon-o-chevron-left class="h-5 w-5" />
                                </button>
                                <button @click="scrollCarousel('carousel-{{ $group['category']->category_id }}', 400)" type="button"
                                    class="rounded-lg border border-gray-200 p-2 text-gray-600 transition-colors hover:bg-gray-100">
                                    <x-heroicon-o-chevron-right class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        <div id="carousel-{{ $group['category']->category_id }}" class="scrollbar-hide flex gap-4 overflow-x-auto transition-all duration-300"
                            style="scroll-behavior: smooth;">
                            @foreach ($group['items'] as $product)
                                <div
                                    class="group shrink-0 w-72 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
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
                                                class="absolute top-3 right-3 bg-green-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                                Fresh
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">
                                            {{ $product->vendor->name ?? 'DormDash' }}
                                        </p>
                                        <h3 class="mt-2 text-base font-bold text-gray-900 truncate" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h3>

                                        <p class="mt-2 text-xs text-gray-600">Stock: {{ $product->stock }} available</p>

                                        <div class="mt-4 flex items-baseline gap-2">
                                            <span class="text-xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                            @if ($product->unit_type)
                                                <span class="text-xs text-gray-500">/{{ $product->unit_type }}</span>
                                            @endif
                                        </div>

                                    <div class="mt-4 flex gap-2">
                                        <form action="{{ route('cart.store') }}" method="POST" class="flex-1 m-0">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $product->item_id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="w-full rounded-lg bg-green-50 border border-green-200 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100">
                                                <x-heroicon-o-shopping-cart class="inline h-4 w-4 mr-1" />
                                                Add to Cart
                                            </button>
                                        </form>
                                        <form action="{{ route('checkout.index') }}" method="GET" class="flex-1 m-0">
                                            <input type="hidden" name="buy_item" value="{{ $product->item_id }}">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit"
                                                class="w-full rounded-lg bg-green-600 py-2 text-xs font-semibold text-white transition-all duration-200 hover:bg-green-700 shadow-sm hover:shadow-md">
                                                Buy Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <x-heroicon-o-inbox class="h-16 w-16 mb-4" />
                    <p class="text-lg font-medium">No products available at the moment.</p>
                </div>
            @endforelse
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
