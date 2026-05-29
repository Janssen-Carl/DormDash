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
            deleteId: 0,
            deleteName: '',
            deleteItems: [],
            deleteMode: 'single',
            openDelete: false,
            
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
            },

            get allSelected() {
                return this.cartData.length > 0 && this.cartData.every(i => this.selectedItems.includes(String(i.item_id)));
            },

            toggleAll(event) {
                let allIds = this.cartData.map(i => String(i.item_id));
                if (event.target.checked) {
                    allIds.forEach(id => { if (!this.selectedItems.includes(id)) this.selectedItems.push(id); });
                } else {
                    this.selectedItems = [];
                }
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
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-semibold text-gray-900 truncate" title="{{ $cart->item->name }}">{{ $cart->item->name }}</h3>
                                            @if($cart->item->is_bundle)
                                                <span class="shrink-0 rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wider">Bundle</span>
                                            @endif
                                        </div>
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
                                        @if($cart->item->discounts->isNotEmpty())
                                            @php $discount = $cart->item->discounts->first(); @endphp
                                            <div class="mt-1.5 flex items-center gap-2 flex-wrap">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600">
                                                    <x-heroicon-s-sparkles class="h-3 w-3" />
                                                    @if($discount->type === 'percentage')
                                                        {{ number_format($discount->value) }}% OFF
                                                    @else
                                                        ₱{{ number_format($discount->value) }} OFF
                                                    @endif
                                                </span>
                                                @if($discount->name)
                                                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-full px-2 py-0.5">{{ $discount->name }}</span>
                                                @endif
                                                <span class="text-[10px] text-green-600 font-medium">
                                                    Save ₱{{ number_format(($cart->item->price - $cart->item->discounted_price) * $cart->quantity, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Stock Info --}}
                                    @php
                                        $stock = $cart->item->effective_stock;
                                        $atMax = $cart->quantity >= $stock;
                                    @endphp
                                    <div class="flex flex-col items-end gap-1">
                                        @if($stock > 0)
                                            <span class="text-[10px] @if($stock <= 5) text-amber-600 font-semibold @else text-gray-400 @endif">
                                                @if($stock <= 5 && $stock > 0) Only @endif {{ $stock }} in stock
                                            </span>
                                        @else
                                            <span class="text-[10px] text-red-500 font-semibold">Out of stock</span>
                                        @endif
                                    </div>

                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-1 shrink-0">
                                        @if($cart->quantity > 1)
                                        <form action="{{ route('cart.update', $cart->item_id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrement">
                                            <button type="submit" class="flex h-7 w-7 items-center justify-center rounded text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900">
                                                <x-heroicon-o-minus class="h-4 w-4" />
                                            </button>
                                        </form>
                                        @else
                                        <span class="flex h-7 w-7 items-center justify-center rounded text-gray-300 cursor-not-allowed">
                                            <x-heroicon-o-minus class="h-4 w-4" />
                                        </span>
                                        @endif

                                        <form action="{{ route('cart.update', $cart->item_id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="set">
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                                min="1" max="{{ $stock }}"
                                                onchange="this.form.submit()"
                                                onfocus="this.select()"
                                                class="w-10 text-center bg-transparent border-none p-0 text-sm font-medium text-gray-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none outline-none"
                                            >
                                        </form>

                                        @if(!$atMax)
                                        <form action="{{ route('cart.update', $cart->item_id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increment">
                                            <button type="submit" class="flex h-7 w-7 items-center justify-center rounded text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900">
                                                <x-heroicon-o-plus class="h-4 w-4" />
                                            </button>
                                        </form>
                                        @else
                                        <span class="flex h-7 w-7 items-center justify-center rounded text-gray-300 cursor-not-allowed">
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                        </span>
                                        @endif
                                    </div>

                                    <span class="w-24 shrink-0 text-right font-semibold text-gray-900">
                                        ₱{{ number_format($cart->item->discounted_price * $cart->quantity, 2) }}
                                    </span>

                                    {{-- Remove Item --}}
                                    <button type="button"
                                            @click="deleteId = {{ $cart->item_id }}; deleteName = '{{ addslashes($cart->item->name) }}'; deleteMode = 'single'; openDelete = true"
                                            class="ml-2 flex items-center justify-center rounded p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500 shrink-0">
                                        <x-heroicon-o-trash class="h-5 w-5" />
                                    </button>
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
                @if($cartData->count() > 0)
                <div class="mb-4 flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-6 py-3">
                    <input type="checkbox" :checked="allSelected" @change="toggleAll($event)" class="rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer h-5 w-5">
                    <span class="text-sm font-semibold text-gray-700">Select All</span>
                    <span class="text-xs text-gray-400" x-text="'(' + selectedItems.length + ' of ' + cartData.length + ' selected)'"></span>
                    <button type="button"
                            @click="deleteItems = [...selectedItems]; deleteMode = 'bulk'; openDelete = true"
                            x-show="selectedItems.length > 0"
                            class="ml-auto inline-flex items-center gap-1.5 rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-bold text-red-600 transition-all duration-200 hover:bg-red-100 hover:border-red-300">
                        <x-heroicon-o-trash class="h-3.5 w-3.5" />
                        Delete
                    </button>
                </div>
                @endif
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

            {{-- Custom Delete Confirmation Modal --}}
            <div x-show="openDelete"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity" @click="openDelete = false"></div>

                <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="openDelete"
                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-sm border border-zinc-100"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                        <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-rose-600"></div>

                        <button type="button"
                                @click="openDelete = false"
                                class="absolute right-4 top-4 rounded-lg p-1.5 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-50 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="px-6 pb-6 pt-8">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <template x-if="deleteMode === 'single'">
                                        <div>
                                            <h3 class="text-lg font-bold text-zinc-900 leading-6">Remove Item</h3>
                                            <p class="mt-1 text-sm text-zinc-500">
                                                Are you sure you want to remove <span class="font-semibold text-zinc-700" x-text="deleteName"></span> from your cart?
                                            </p>
                                        </div>
                                    </template>
                                    <template x-if="deleteMode === 'bulk'">
                                        <div>
                                            <h3 class="text-lg font-bold text-zinc-900 leading-6">Remove Selected Items</h3>
                                            <p class="mt-1 text-sm text-zinc-500">
                                                Are you sure you want to remove the <span class="font-semibold text-zinc-700" x-text="deleteItems.length"></span> selected item(s) from your cart?
                                            </p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-7 flex flex-col sm:flex-row-reverse gap-3 border-t border-zinc-100 pt-5">
                                <template x-if="deleteMode === 'single'">
                                    <form :action="'/cart/' + deleteId" method="POST" class="flex-1 w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-red-700 focus:outline-none transition-all active:scale-98">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Remove
                                        </button>
                                    </form>
                                </template>
                                <template x-if="deleteMode === 'bulk'">
                                    <form action="/cart/delete-selected" method="POST" class="flex-1 w-full">
                                        @csrf
                                        <template x-for="id in deleteItems" :key="id">
                                            <input type="hidden" name="item_ids[]" :value="id">
                                        </template>
                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-red-700 focus:outline-none transition-all active:scale-98">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </template>
                                <button type="button"
                                        @click="openDelete = false"
                                        class="flex-1 w-full rounded-xl bg-zinc-100 px-4 py-3 text-sm font-bold text-zinc-700 hover:bg-zinc-200 focus:outline-none transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
