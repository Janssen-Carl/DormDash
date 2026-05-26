@extends('layouts.main')

@section('title', $item->name . ' - DormDash')

@section('content')
    <div class="mx-auto max-w-7xl px-8 py-12">
        @if(session('success'))
            <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-2xl border border-green-500" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                <x-heroicon-s-check-circle class="h-5 w-5 text-green-200" />
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="/home" class="hover:text-green-600 transition-colors">Home</a>
            <x-heroicon-o-chevron-right class="h-3 w-3" />
            <a href="/products" class="hover:text-green-600 transition-colors">Products</a>
            <x-heroicon-o-chevron-right class="h-3 w-3" />
            <span class="text-gray-900 font-medium truncate max-w-xs">{{ $item->name }}</span>
        </div>

        <a href="javascript:history.back()" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-900 mb-8">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div x-data="{ 
                mainImage: '{{ $item->images->first() ? asset($item->images->first()->image) : '' }}',
                selectedIndex: 0
            }">
                <div class="aspect-square rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden border border-gray-100">
                    @if ($item->images->first())
                        <img :src="mainImage" alt="{{ $item->name }}"
                             class="h-full w-full object-cover" />
                    @else
                        <div class="flex h-full w-full items-center justify-center text-gray-300">
                            <x-heroicon-o-photo class="h-24 w-24" />
                        </div>
                    @endif
                </div>

                @if ($item->images->count() > 1)
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                        @foreach ($item->images as $index => $image)
                            <button type="button" 
                                    @click="mainImage = '{{ asset($image->image) }}'; selectedIndex = {{ $index }}"
                                    :class="selectedIndex === {{ $index }} ? 'border-green-500 ring-2 ring-green-200' : 'border-gray-200 hover:border-gray-300'"
                                    class="h-20 w-20 shrink-0 rounded-lg border-2 overflow-hidden bg-gray-100 transition-all">
                                <img src="{{ asset($image->image) }}" class="h-full w-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">
                    {{ $item->vendor->name ?? 'DormDash' }}
                </p>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $item->name }}</h1>

                <div class="mt-2 flex flex-wrap items-center gap-2">
                    @if($item->is_bundle)
                        <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-700 uppercase tracking-wider">Bundle</span>
                    @endif
                    @if($item->is_available && $item->stock > 0)
                        <span class="rounded bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">In Stock</span>
                    @endif
                    @if($item->discounts->isNotEmpty())
                        @php $discount = $item->discounts->first(); @endphp
                        <span class="rounded bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700 uppercase tracking-wider animate-pulse">
                            @if ($discount->type === 'percentage')
                                {{ number_format($discount->value) }}% OFF
                            @else
                                ₱{{ number_format($discount->value) }} OFF
                            @endif
                        </span>
                    @endif
                </div>

                <div class="mt-6 flex items-baseline gap-3">
                    @if ($item->discounts->isNotEmpty())
                        <span class="text-4xl font-bold text-green-600">₱{{ number_format($item->discounted_price, 2) }}</span>
                        <span class="text-xl text-gray-400 line-through">₱{{ number_format($item->price, 2) }}</span>
                    @else
                        <span class="text-4xl font-bold text-gray-900">₱{{ number_format($item->price, 2) }}</span>
                    @endif
                    @if ($item->unit_type)
                        <span class="text-lg text-gray-500">/{{ (int) $item->unit_value }} {{ $item->unit_type }}</span>
                    @endif
                </div>

                @if ($item->discounts->isNotEmpty())
                    @php $discount = $item->discounts->first(); @endphp
                    <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-sm font-semibold text-red-600">
                        <x-heroicon-o-sparkles class="h-4 w-4" />
                        @if ($discount->type === 'percentage')
                            {{ number_format($discount->value) }}% OFF
                        @else
                            ₱{{ number_format($discount->value, 2) }} OFF
                        @endif
                    </div>
                @endif

                @if($item->is_bundle && $item->bundles->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900">Bundle Contents</h3>
                    <div class="mt-3 space-y-3">
                        @foreach($item->bundles as $child)
                        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-gray-200">
                                @if($child->images->first())
                                    <img src="{{ asset($child->images->first()->image) }}" alt="{{ $child->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-400">
                                        <x-heroicon-o-photo class="h-6 w-6" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $child->name }}</p>
                                <p class="text-sm text-gray-500">₱{{ number_format($child->price, 2) }} each</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-lg font-bold text-gray-900">x{{ $child->pivot->quantity }}</p>
                                <p class="text-xs text-gray-500">Quantity</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900">Description</h3>
                    <p class="mt-2 text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $item->description ?? 'No description available for this product.' }}
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Brand</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $item->brand ?? 'N/A' }}</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Stock</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $item->stock }} available</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Sales</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $item->sold }} items sold</p>
                    </div>
                    <!-- @if ($item->sku)
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">SKU</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $item->sku }}</p>
                    </div>
                    @endif -->
                    @if ($item->unit_type)
                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Unit</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ (int) $item->unit_value }} {{ $item->unit_type }}</p>
                    </div>
                    @endif
                </div>

                @if ($item->categories->isNotEmpty())
                <div class="mt-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Categories</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($item->categories as $category)
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                <x-heroicon-o-tag class="mr-1.5 h-3 w-3" />
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($item->is_available && $item->stock > 0)
                <div class="mt-10 flex flex-col gap-4">
                    <form x-data="{ qty: 1, maxQty: {{ $item->stock }} }" action="{{ route('cart.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                        
                        <div class="flex items-center gap-3 mb-4">
                            <label class="text-sm font-semibold text-gray-700">Quantity:</label>
                            <div class="flex items-center rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                                <button type="button" @click="qty = Math.max(1, qty - 1)" 
                                        class="px-3 py-2 text-gray-400 hover:text-green-600 hover:bg-gray-50 transition-colors"
                                        :disabled="qty <= 1">
                                    <x-heroicon-o-minus class="h-4 w-4" />
                                </button>
                                <input type="number" name="quantity" x-model.number="qty" min="1" max="{{ $item->stock }}"
                                       class="w-14 text-center bg-transparent border-none p-0 text-sm font-semibold text-gray-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                                <button type="button" @click="qty = Math.min(maxQty, qty + 1)" 
                                        class="px-3 py-2 text-gray-400 hover:text-green-600 hover:bg-gray-50 transition-colors"
                                        :disabled="qty >= maxQty">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                </button>
                            </div>
                            <span class="text-xs text-gray-500">of {{ $item->stock }} available</span>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 rounded-xl bg-green-50 border border-green-200 py-3.5 text-sm font-bold text-green-600 transition-all hover:bg-green-100">
                                <x-heroicon-o-shopping-cart class="inline h-5 w-5 mr-2" />
                                Add to Cart
                            </button>
                        </div>
                    </form>

                    <form x-data="{ qty: 1 }" action="{{ route('checkout.index') }}" method="GET" class="flex-1">
                        <input type="hidden" name="buy_item" value="{{ $item->item_id }}">
                        <input type="hidden" name="qty" x-model.number="qty" value="1">
                        <button type="submit"
                            class="w-full rounded-xl bg-green-600 py-3.5 text-sm font-bold text-white transition-all hover:bg-green-700 shadow-sm">
                            Buy Now
                        </button>
                    </form>
                </div>
                @else
                <div class="mt-10">
                    <div class="rounded-xl bg-gray-100 py-3.5 text-center text-sm font-semibold text-gray-500">
                        <x-heroicon-o-x-circle class="inline h-5 w-5 mr-2" />
                        Currently Unavailable
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="mt-20">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">You May Also Like</h2>
                <a href="/products" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                    View All Products
                    <x-heroicon-o-arrow-right class="inline h-4 w-4 ml-1" />
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $product)
                    <a href="{{ route('products.show', ['id' => $product->item_id]) }}"
                       class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:border-gray-200">
                        <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if ($product->images->first())
                                <img src="{{ asset($product->images->first()->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300" />
                            @else
                                <div class="flex h-full w-full items-center justify-center text-gray-300 group-hover:scale-110 transition-transform duration-300">
                                    <x-heroicon-o-photo class="h-16 w-16" />
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">
                                {{ $product->vendor->name ?? 'DormDash' }}
                            </p>
                            <div class="mt-2 flex items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                                @if($product->is_bundle)
                                    <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wider shrink-0">Bundle</span>
                                @endif
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span>Stock: {{ $product->stock }} available</span>
                                <span class="font-semibold text-gray-700 bg-gray-100/70 px-2 py-0.5 rounded-full">{{ $product->sold }} sold</span>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                @if ($product->unit_type)
                                    <span class="text-xs text-gray-500">/{{ (int) $product->unit_value }} {{ $product->unit_type }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>
@endsection
