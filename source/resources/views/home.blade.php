@extends('layouts.main')

@section('title', 'Home - DormDash')

@section('content')
        @if(session('success'))
            <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-2xl border border-green-500" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                <x-heroicon-s-check-circle class="h-5 w-5 text-green-200" />
                {{ session('success') }}
            </div>
        @endif

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
                            <a href="/products?has_discount=1"
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
                             <a href="{{ route('products.show', ['id' => $product->item_id]) }}" class="block relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
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

                                 @if ($product->discounts->isNotEmpty())
                                     @php $discount = $product->discounts->first(); @endphp
                                     <div
                                         class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm z-10">
                                         @if ($discount->type === 'percentage')
                                             {{ number_format($discount->value) }}% OFF
                                         @else
                                             ₱{{ number_format($discount->value) }} OFF
                                         @endif
                                     </div>
                                 @endif
                             </a>
                             <div class="p-4">
                                 <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">
                                     {{ $product->vendor->name ?? 'DormDash' }}
                                 </p>
                                 <a href="{{ route('products.show', ['id' => $product->item_id]) }}" class="mt-2 block text-base font-bold text-gray-900 hover:text-green-600 transition-colors truncate">{{ $product->name }}</a>

                                 <p class="mt-2 text-xs text-gray-600">Stock: {{ $product->stock }} available</p>

                                  <div class="mt-4 flex items-baseline gap-2">
                                     @if ($product->discounts->isNotEmpty())
                                         <span class="text-xl font-bold text-green-600">₱{{ number_format($product->discounted_price, 2) }}</span>
                                         <span class="text-xs text-gray-400 line-through">₱{{ number_format($product->price, 2) }}</span>
                                     @else
                                         <span class="text-xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                     @endif
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
        <section class="px-8 py-16 bg-gradient-to-b from-white to-gray-50/50">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    {{-- Left Sidebar with Offers List --}}
                    <div class="col-span-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 border border-red-100 text-red-600 text-xs font-bold w-fit mb-4">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            Flash Deals
                        </span>
                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 leading-tight">Today's <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-rose-500">Exclusive Offers</span></h2>
                        <p class="mt-3 text-sm text-gray-500 leading-relaxed">Don't miss out on these limited-time deals! Grab them before they sell out.</p>
 
                        <div class="mt-6 space-y-3">
                            <a href="/products?has_discount=1"
                                class="flex items-center gap-2 text-green-600 font-bold text-sm hover:text-green-700 transition-colors group">
                                <x-heroicon-o-arrow-right class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" />
                                See All Active Offers
                            </a>
                            <a href="/products?has_discount=1"
                                class="inline-flex h-12 items-center justify-center rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 text-sm font-bold text-white transition-all hover:from-green-700 hover:to-emerald-600 w-full shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                Grab A Deal Now
                            </a>
                        </div>
                    </div>
 
                    {{-- Right Grid with Offer Cards --}}
                    <div class="col-span-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @php
                                $offerGradients = [
                                    'from-emerald-50 to-green-100',
                                    'from-blue-50 to-indigo-100',
                                    'from-orange-50 to-yellow-100',
                                    'from-purple-50 to-pink-100',
                                    'from-rose-50 to-red-100',
                                    'from-teal-50 to-cyan-100',
                                ];
                                $offerEmojis = ['💰', '📦', '🍰', '✨', '🎁', '🛒'];
                            @endphp
 
                            @forelse ($offers as $index => $offer)
                                <a href="{{ route('products.show', ['id' => $offer->item_id]) }}"
                                     class="group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
                                     
                                     {{-- Image Container --}}
                                     <div class="h-44 relative bg-gray-50 overflow-hidden">
                                         @if ($offer->item && $offer->item->images->first())
                                             <img src="{{ asset($offer->item->images->first()->image) }}"
                                                  alt="{{ $offer->item->name }}"
                                                  class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                         @else
                                             <div class="flex h-full w-full items-center justify-center bg-gradient-to-br {{ $offerGradients[$index % count($offerGradients)] }} group-hover:scale-105 transition-transform duration-500">
                                                 <span class="text-6xl">{{ $offerEmojis[$index % count($offerEmojis)] }}</span>
                                             </div>
                                         @endif
 
                                         {{-- Floating Discount Badge --}}
                                         <div class="absolute top-4 left-4 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[11px] font-black tracking-wider uppercase px-3 py-1.5 rounded-full shadow-md z-10">
                                             @if ($offer->type === 'percentage')
                                                 {{ number_format($offer->value) }}% OFF
                                             @else
                                                 ₱{{ number_format($offer->value) }} OFF
                                             @endif
                                         </div>
                                     </div>
 
                                     {{-- Details --}}
                                     <div class="p-5 flex flex-col justify-between flex-grow">
                                         <div>
                                             <span class="text-[10px] font-extrabold text-green-600 uppercase tracking-widest block mb-1">
                                                 {{ $offer->item->vendor->name ?? 'DormDash Exclusive' }}
                                             </span>
                                             <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider line-clamp-1">
                                                 {{ $offer->name }}
                                             </h3>
                                             <h4 class="mt-1.5 text-base font-bold text-gray-900 group-hover:text-green-600 transition-colors line-clamp-1">
                                                 {{ $offer->item->name ?? 'Special Product' }}
                                             </h4>
                                         </div>
 
                                         <div class="mt-4 pt-4 border-t border-gray-50">
                                             <div class="flex items-baseline gap-2">
                                                 @if ($offer->item)
                                                     <span class="text-xl font-black text-rose-600">
                                                         ₱{{ number_format($offer->item->discounted_price, 2) }}
                                                     </span>
                                                     <span class="text-xs text-gray-400 line-through">
                                                         ₱{{ number_format($offer->item->price, 2) }}
                                                     </span>
                                                 @endif
                                             </div>
 
                                             <div class="mt-3 flex items-center justify-between text-xs font-bold text-green-600 group-hover:text-green-700 transition-colors">
                                                 <span class="text-[10px] text-gray-400 font-medium">Limited time only</span>
                                                 <span class="inline-flex items-center gap-1">
                                                     Claim Deal
                                                     <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                                     </svg>
                                                 </span>
                                             </div>
                                         </div>
                                     </div>
                                 </a>
                             @empty
                                 {{-- Fallback static cards when no active offers exist --}}
                                 <div class="group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
                                     <div class="h-44 flex items-center justify-center bg-gradient-to-br from-emerald-50 to-green-100 group-hover:scale-105 transition-transform duration-500">
                                         <span class="text-6xl">🍕</span>
                                     </div>
                                     <div class="p-5 flex flex-col justify-between flex-grow">
                                         <div>
                                             <span class="text-[10px] font-extrabold text-green-600 uppercase tracking-widest block mb-1">MIDNIGHT FLAVORS</span>
                                             <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Late Night Craving</h3>
                                             <h4 class="mt-1.5 text-base font-bold text-gray-900">Midnight Munchies Pack</h4>
                                         </div>
                                         <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                             <span class="text-sm font-bold text-red-500">Up to 30% OFF</span>
                                             <span class="text-[10px] text-gray-400 font-medium">Starts 10 PM</span>
                                         </div>
                                     </div>
                                 </div>
 
                                 <div class="group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
                                     <div class="h-44 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 group-hover:scale-105 transition-transform duration-500">
                                         <span class="text-6xl">📦</span>
                                     </div>
                                     <div class="p-5 flex flex-col justify-between flex-grow">
                                         <div>
                                             <span class="text-[10px] font-extrabold text-green-600 uppercase tracking-widest block mb-1">STUDENT SPECIALS</span>
                                             <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dorm essentials</h3>
                                             <h4 class="mt-1.5 text-base font-bold text-gray-900">Dorm Starter Bundles</h4>
                                         </div>
                                         <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                             <span class="text-sm font-bold text-blue-500">Save ₱150</span>
                                             <span class="text-[10px] text-gray-400 font-medium">Available Now</span>
                                         </div>
                                     </div>
                                 </div>
 
                                 <div class="group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
                                     <div class="h-44 flex items-center justify-center bg-gradient-to-br from-orange-50 to-yellow-100 group-hover:scale-105 transition-transform duration-500">
                                         <span class="text-6xl">🍹</span>
                                     </div>
                                     <div class="p-5 flex flex-col justify-between flex-grow">
                                         <div>
                                             <span class="text-[10px] font-extrabold text-green-600 uppercase tracking-widest block mb-1">FLASH SIPS</span>
                                             <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Happy Hour Deals</h3>
                                             <h4 class="mt-1.5 text-base font-bold text-gray-900">Soda & Drinks Combo</h4>
                                         </div>
                                         <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                             <span class="text-sm font-bold text-orange-500">Buy 1 Get 1 FREE</span>
                                             <span class="text-[10px] text-gray-400 font-medium">Limited Stock</span>
                                         </div>
                                     </div>
                                 </div>
                             @endforelse
                         </div>
                     </div>
                 </div>
             </div>
         </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
