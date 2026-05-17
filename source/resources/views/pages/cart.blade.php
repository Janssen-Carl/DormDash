@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
    <div class="mx-auto max-w-7xl px-8 pt-8 pb-12">
        <h1 class="mb-8 text-4xl font-bold tracking-tight text-gray-900">Your Shopping Cart</h1>

        @if(session('success'))
            <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-lg bg-green-100 p-4 text-sm font-medium text-green-800 shadow-xl border border-green-300" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
                <x-heroicon-s-check-circle class="h-5 w-5 text-green-600" />
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="col-span-1 lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white">
                    @forelse ($cartItems as $cart)
                        <div class="flex items-center gap-4 border-b border-gray-200 p-6 last:border-b-0">
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-gray-200 relative">
                                @if ($cart->item->images->first())
                                    <img src="{{ asset($cart->item->images->first()->image) }}" alt="{{ $cart->item->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-400">
                                        <x-heroicon-o-photo class="h-8 w-8" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate" title="{{ $cart->item->name }}">{{ $cart->item->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    ₱{{ number_format($cart->item->price, 2) }}
                                    @if($cart->item->unit_type)
                                        /{{ $cart->item->unit_type }}
                                    @endif
                                </p>
                                <p class="text-xs text-green-600 font-medium mt-0.5">{{ $cart->item->vendor->name ?? '' }}</p>
                            </div>

                            {{-- Quantity Controls --}}
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-1 shrink-0">
                                <form action="{{ route('cart.update', $cart->item_id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="decrement">
                                    <button type="submit" class="flex h-7 w-7 items-center justify-center rounded text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900">
                                        <x-heroicon-o-minus class="h-4 w-4" />
                                    </button>
                                </form>

                                <span class="w-6 text-center text-sm font-medium">{{ $cart->quantity }}</span>

                                <form action="{{ route('cart.update', $cart->item_id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="increment">
                                    <button type="submit" class="flex h-7 w-7 items-center justify-center rounded text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900">
                                        <x-heroicon-o-plus class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>

                            <span class="w-24 shrink-0 text-right font-semibold text-gray-900">
                                ₱{{ number_format($cart->item->price * $cart->quantity, 2) }}
                            </span>

                            {{-- Remove Item --}}
                            <form action="{{ route('cart.destroy', $cart->item_id) }}" method="POST" class="m-0 shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 flex items-center justify-center rounded p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-300" />
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                            <p class="mt-2 text-sm text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                            <a href="/products" class="mt-6 inline-flex rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-700 shadow-sm">
                                Start Shopping
                            </a>
                        </div>
                    @endforelse
                </div>

                @if($cartItems->count() > 0)
                    <div class="mt-4 text-sm text-gray-600">
                        <a href="/products" class="inline-flex items-center gap-1 font-semibold text-green-600 transition-colors hover:text-green-700">
                            <x-heroicon-o-arrow-left class="h-4 w-4" />
                            Continue Shopping
                        </a>
                    </div>
                @endif
            </div>

            {{-- Order Summary --}}
            <aside class="col-span-1">
                <div class="rounded-xl border border-gray-200 bg-white p-6 sticky top-24">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Order Summary</h2>

                    <div class="space-y-3 border-b border-gray-200 pb-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Delivery Fee</span>
                            <span>₱{{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax (Included)</span>
                            <span>₱0.00</span>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="button" class="mt-6 w-full rounded-lg bg-green-600 py-3 font-semibold text-white transition-colors hover:bg-green-700 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $cartItems->count() == 0 ? 'disabled' : '' }}>
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
