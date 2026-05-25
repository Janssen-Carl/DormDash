@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('content')
    <div class="mx-auto max-w-7xl px-8 pt-8 pb-12">
        <h1 class="mb-8 text-4xl font-bold tracking-tight text-gray-900">Your Shopping Cart</h1>

        @if(session('success'))
            <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-2xl border border-green-500" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                <x-heroicon-s-check-circle class="h-5 w-5 text-green-200" />
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{
            cartData: {{ Js::from($cartData) }},
            selectedItems: {{ Js::from($cartData->pluck('item_id')->map(fn($id) => (string)$id)) }},
            
            init() {
                let savedSelected = sessionStorage.getItem('cartSelectedItems');
                let savedKnown = sessionStorage.getItem('cartKnownItems');
                
                let currentIds = this.cartData.map(i => String(i.item_id));
                
                if (savedSelected && savedKnown) {
                    let selectedArray = JSON.parse(savedSelected);
                    let knownArray = JSON.parse(savedKnown);
                    
                    // Any id that is in currentIds but NOT in knownArray is a NEW item, so select it by default.
                    let newIds = currentIds.filter(id => !knownArray.includes(id));
                    
                    this.selectedItems = selectedArray
                        .filter(id => currentIds.includes(id)) // keep valid selections
                        .concat(newIds); // add newly added items
                }
                
                // Update known items
                sessionStorage.setItem('cartKnownItems', JSON.stringify(currentIds));
                // Ensure initial save
                sessionStorage.setItem('cartSelectedItems', JSON.stringify(this.selectedItems));
                
                this.$watch('selectedItems', value => {
                    sessionStorage.setItem('cartSelectedItems', JSON.stringify(value));
                });
            },
            
            get subtotal() {
                return this.cartData
                    .filter(item => this.selectedItems.includes(String(item.item_id)))
                    .reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
            },
            
            get itemCount() {
                return this.cartData
                    .filter(item => this.selectedItems.includes(String(item.item_id)))
                    .reduce((sum, item) => sum + item.quantity, 0);
            },
            
            get deliveryFee() {
                return this.selectedItems.length > 0 ? 50.00 : 0.00;
            },
            
            get total() {
                return this.subtotal + this.deliveryFee;
            },
            
            toggleVendor(vendorId, event) {
                let itemsForVendor = this.cartData.filter(i => String(i.vendor_id) === String(vendorId)).map(i => String(i.item_id));
                if (event.target.checked) {
                    itemsForVendor.forEach(id => {
                        if (!this.selectedItems.includes(id)) this.selectedItems.push(id);
                    });
                } else {
                    this.selectedItems = this.selectedItems.filter(id => !itemsForVendor.includes(id));
                }
            },
            
            isVendorSelected(vendorId) {
                let itemsForVendor = this.cartData.filter(i => String(i.vendor_id) === String(vendorId)).map(i => String(i.item_id));
                return itemsForVendor.length > 0 && itemsForVendor.every(id => this.selectedItems.includes(id));
            },
            
            formatCurrency(value) {
                return new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            }
        }">
            {{-- Cart Items --}}
            <div class="col-span-1 lg:col-span-2">
                @forelse ($groupedCartItems as $vendorId => $vendorItems)
                    @php
                        $vendor = $vendorItems->first()->item->vendor;
                        $vendorName = $vendor ? $vendor->name : 'DormDash';
                    @endphp
                    <div class="rounded-xl border border-gray-200 bg-white mb-6 overflow-hidden">
                        {{-- Vendor Header --}}
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center gap-3">
                            <input type="checkbox" :checked="isVendorSelected('{{ $vendorId }}')" @change="toggleVendor('{{ $vendorId }}', $event)" class="rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer h-5 w-5">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-building-storefront class="h-5 w-5 text-gray-500" />
                                <h2 class="font-bold text-gray-900">{{ $vendorName }}</h2>
                            </div>
                        </div>

                        {{-- Items List --}}
                        <div>
                            @foreach ($vendorItems as $cart)
                                <div class="flex items-center gap-4 border-b border-gray-200 p-6 last:border-b-0">
                                    <div class="shrink-0 flex items-center h-full">
                                        <input type="checkbox" value="{{ $cart->item_id }}" x-model="selectedItems" class="rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer h-5 w-5">
                                    </div>
                                    
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
                                            @if($cart->item->discounts->isNotEmpty())
                                                <span class="text-green-600 font-bold">₱{{ number_format($cart->item->discounted_price, 2) }}</span>
                                                <span class="text-xs text-gray-400 line-through">₱{{ number_format($cart->item->price, 2) }}</span>
                                            @else
                                                ₱{{ number_format($cart->item->price, 2) }}
                                            @endif
                                            @if($cart->item->unit_type)
                                                /{{ $cart->item->unit_type }}
                                            @endif
                                        </p>
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
                                        ₱{{ number_format($cart->item->discounted_price * $cart->quantity, 2) }}
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
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                        <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-300" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                        <p class="mt-2 text-sm text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                        <a href="/products" class="mt-6 inline-flex rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-700 shadow-sm">
                            Start Shopping
                        </a>
                    </div>
                @endforelse

                @if($cartData->count() > 0)
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
                            <span>Subtotal (<span x-text="itemCount"></span> items)</span>
                            <span>₱<span x-text="formatCurrency(subtotal)"></span></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Delivery Fee</span>
                            <span>₱<span x-text="formatCurrency(deliveryFee)"></span></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax (Included)</span>
                            <span>₱0.00</span>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span>₱<span x-text="formatCurrency(total)"></span></span>
                    </div>

                    <form action="/checkout" method="GET" class="mt-6">
                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="selected_items[]" :value="id">
                        </template>
                        <button type="submit" class="w-full rounded-lg bg-green-600 py-3 font-semibold text-white transition-colors hover:bg-green-700 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" :disabled="selectedItems.length === 0">
                            Proceed to Checkout
                        </button>
                    </form>

                    <button type="button" class="mt-3 w-full rounded-lg border border-gray-200 py-3 text-sm font-semibold text-gray-900 transition-colors hover:bg-gray-50">
                        Apply Promo Code
                    </button>
                </div>
            </aside>
        </div>
    </div>
@endsection
