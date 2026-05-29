@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
<div class="mx-auto max-w-7xl px-8 py-12">
    <div class="mb-8 flex items-center gap-4">
        <a href="javascript:history.back()" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-900">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Checkout</h1>
    </div>

    @if($errors->any())
        <div class="mb-8 rounded-lg bg-red-50 p-4 text-sm text-red-700 shadow-sm border border-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        @csrf
        
        {{-- Hidden State Inputs --}}
        @if(isset($sourceType))
            <input type="hidden" name="source" value="{{ $sourceType }}">
            @if($sourceType === 'reorder')
                <input type="hidden" name="reorder_id" value="{{ $sourceData }}">
            @elseif($sourceType === 'buy_now')
                <input type="hidden" name="buy_item" value="{{ $sourceData['item_id'] }}">
                <input type="hidden" name="qty" value="{{ $sourceData['qty'] }}">
            @elseif($sourceType === 'cart' && is_array($sourceData))
                @foreach($sourceData as $id)
                    <input type="hidden" name="selected_items[]" value="{{ $id }}">
                @endforeach
            @endif
        @endif
        
        {{-- Left Column: Details --}}
        <div class="col-span-1 lg:col-span-2 space-y-8">
            {{-- Shipping Address --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600">
                        <x-heroicon-s-map-pin class="h-5 w-5" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Delivery Address</h2>
                </div>
                
                @if(count($addresses) > 0)
                    <div class="space-y-4">
                        @foreach($addresses as $address)
                            <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-5 transition-colors hover:border-green-600 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                <div class="flex h-5 items-center">
                                    <input type="radio" name="address_id" value="{{ $address->address_id }}" class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-600" {{ $loop->first ? 'checked' : '' }}>
                                </div>
                                <div>
                                    @php
                                        $user = auth()->user();
                                        $isDefault = false;
                                        if ($user->role === 'vendor') {
                                            $isDefault = ($user->vendor->address_id ?? null) == $address->address_id;
                                        } else {
                                            $isDefault = ($user->customer->primary_address_id ?? null) == $address->address_id;
                                        }
                                    @endphp
                                    <p class="font-semibold text-gray-900">{{ $isDefault ? 'Primary Address' : 'Secondary Address' }}</p>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ current(array_filter([$address->street, $address->city, $address->province_state])) ? implode(', ', array_filter([$address->street, $address->city, $address->province_state])) : 'No address details provided' }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $address->phone ?: 'No contact number' }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                        <x-heroicon-o-home class="mx-auto h-8 w-8 text-gray-400 mb-2" />
                        <p class="text-sm font-medium text-gray-900">No saved addresses</p>
                        <p class="text-xs text-gray-500 mt-1 mb-4">You need an address to receive your delivery.</p>
                        <a href="/profile/edit" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            Add Address in Profile
                        </a>
                    </div>
                @endif
            </div>

            {{-- Payment Method --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm" x-data="{ paymentMethod: 'cod', selectedCard: '{{ count($cards) > 0 ? $cards->first()->banking_id : 'new' }}' }">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                        <x-heroicon-s-credit-card class="h-5 w-5" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex cursor-pointer items-center gap-4 rounded-xl border border-gray-200 p-5 transition-colors hover:border-blue-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                        <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-banknotes class="h-6 w-6 text-gray-500" />
                            <span class="font-semibold text-gray-900">Cash on Delivery</span>
                        </div>
                    </label>

                    <label class="flex cursor-pointer items-center gap-4 rounded-xl border border-gray-200 p-5 transition-colors hover:border-blue-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                        <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-credit-card class="h-6 w-6 text-gray-500" />
                            <span class="font-semibold text-gray-900">Credit / Debit Card</span>
                        </div>
                    </label>
                </div>

                {{-- Saved Cards & Tokenized Form (Visible when Card is chosen) --}}
                <div x-show="paymentMethod === 'card'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8 space-y-6 border-t border-gray-100 pt-6">
                    @if(count($cards) > 0)
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Saved Payment Cards</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($cards as $card)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition-colors hover:border-blue-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="card_id" value="{{ $card->banking_id }}" x-model="selectedCard" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                        <div class="flex-1 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-950 uppercase text-xs tracking-wider bg-gray-100 px-2 py-0.5 rounded">{{ $card->payment_method }}</span>
                                                <span class="text-sm font-semibold text-gray-800">•••• {{ $card->acc_last4_no }}</span>
                                            </div>
                                            <span class="text-xs text-gray-400 font-mono font-medium">{{ $card->token }}</span>
                                        </div>
                                    </label>
                                @endforeach
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition-colors hover:border-blue-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="card_id" value="new" x-model="selectedCard" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <span class="text-sm font-bold text-gray-700">Use a New Credit / Debit Card</span>
                                </label>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="card_id" value="new">
                    @endif

                    {{-- Secure Card Number Inputs --}}
                    <div x-show="selectedCard === 'new'" class="space-y-4 rounded-2xl bg-gray-50/50 p-6 border border-gray-100">
                        <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-wider mb-2">
                            <x-heroicon-s-shield-check class="h-4 w-4 text-blue-500" />
                            Secure Input
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Cardholder Name</label>
                                <input type="text" name="new_card_name" placeholder="JOHN DOE" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 uppercase font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Card Type</label>
                                <select name="new_card_type" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold">
                                    <option value="visa">Visa</option>
                                    <option value="mastercard">Mastercard</option>
                                    <option value="amex">American Express</option>
                                    <option value="discover">Discover</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Card Number</label>
                                <div class="relative">
                                    <input type="text" name="new_card_number" placeholder="4111 2222 3333 4444" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 pl-10 font-mono tracking-widest">
                                    <x-heroicon-o-credit-card class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
                                    🔒 DormDash stores only the last 4 digits.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Order Summary --}}
        <div class="col-span-1" x-data="{ 
            items: {{ Js::from($items->map(fn($e) => ['id' => $e->item->item_id, 'price' => (float)$e->price, 'quantity' => (int)$e->quantity])) }},
            deliveryFee: {{ $deliveryFee }},
            openDeleteCheckout: false,
            itemToDeleteCheckout: null,
            get subtotal() { return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0); },
            get totalQuantity() { return this.items.reduce((sum, item) => sum + parseInt(item.quantity), 0); },
            get total() { return this.subtotal + this.deliveryFee; },
            formatPrice(price) { return price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); },
            removeCheckoutItem() {
                let item = this.itemToDeleteCheckout;
                if (!item) return;
                this.items.splice(item.index, 1);
                fetch('/cart/' + item.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]')?.getAttribute('content') || '', 'X-Requested-With': 'XMLHttpRequest' } }).catch(function(e){ console.error('Checkout delete error:', e); });
                this.openDeleteCheckout = false;
                this.itemToDeleteCheckout = null;
            }
        }">
            <div class="sticky top-8 rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    @foreach($items as $index => $entry)
                        <div class="flex gap-4">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center">
                                @if ($entry->item->images->first())
                                    <img src="{{ asset($entry->item->images->first()->image) }}" alt="{{ $entry->item->name }}" class="h-full w-full object-cover" />
                                @else
                                    <x-heroicon-o-photo class="h-6 w-6 text-gray-400" />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="truncate text-sm font-semibold text-gray-900">{{ $entry->item->name }}</h4>
                                    @if($entry->item->is_bundle)
                                        <span class="shrink-0 rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wider">Bundle</span>
                                    @endif
                                </div>
                                @if($entry->item->discounts->isNotEmpty())
                                    @php $discount = $entry->item->discounts->first(); @endphp
                                    <div class="mt-1 flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600">
                                            @if($discount->type === 'percentage')
                                                {{ number_format($discount->value) }}% OFF
                                            @else
                                                ₱{{ number_format($discount->value) }} OFF
                                            @endif
                                        </span>
                                        @if($discount->name)
                                            <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-full px-2 py-0.5">{{ $discount->name }}</span>
                                        @endif
                                        <span class="text-[10px] text-gray-400 line-through">₱{{ number_format($entry->item->price, 2) }}</span>
                                    </div>
                                @endif
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-xs text-gray-500 font-medium">Qty</span>
                                    <div class="flex items-center rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                                        <button type="button" @click="if(items[{{ $index }}].quantity > 1) items[{{ $index }}].quantity--" class="px-2 py-1.5 text-gray-400 hover:text-green-600 hover:bg-gray-50 transition-colors focus:outline-none">
                                            <x-heroicon-o-minus class="h-3 w-3" />
                                        </button>
                                        <input type="number" name="quantities[{{ $entry->item->item_id }}]" x-model.number="items[{{ $index }}].quantity" min="1" max="{{ $entry->item->effective_stock }}" @input="if (items[{{ $index }}].quantity < 1) items[{{ $index }}].quantity = 1; if (items[{{ $index }}].quantity > {{ $entry->item->effective_stock }}) items[{{ $index }}].quantity = {{ $entry->item->effective_stock }}" class="w-10 text-center bg-transparent border-none p-0 text-xs font-semibold text-gray-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <button type="button" @click="if(items[{{ $index }}].quantity < {{ $entry->item->effective_stock }}) items[{{ $index }}].quantity++" class="px-2 py-1.5 text-gray-400 hover:text-green-600 hover:bg-gray-50 transition-colors focus:outline-none">
                                            <x-heroicon-o-plus class="h-3 w-3" />
                                        </button>
                                    </div>
                                    @if($entry->item->effective_stock <= 5)
                                        <span class="text-[10px] text-amber-600 font-semibold">Only {{ $entry->item->effective_stock }} left</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <p class="text-sm font-semibold text-gray-900" x-text="'₱' + formatPrice(items[{{ $index }}].price * items[{{ $index }}].quantity)"></p>
                                    <button type="button" @click="itemToDeleteCheckout = { index: {{ $index }}, id: {{ $entry->item->item_id }}, name: '{{ addslashes($entry->item->name) }}' }; openDeleteCheckout = true" class="ml-auto text-gray-400 hover:text-red-500 transition-colors">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-3 border-t border-gray-100 pt-6">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span x-text="'Subtotal (' + totalQuantity + ' items)'"></span>
                        <span x-text="'₱' + formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Delivery Fee</span>
                        <span>₱{{ number_format($deliveryFee, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-green-600" x-text="'₱' + formatPrice(total)"></span>
                </div>

                <button type="submit" class="mt-8 w-full rounded-xl bg-green-600 py-4 text-sm font-bold text-white shadow-sm transition-all hover:bg-green-700 hover:shadow">
                    Place Order Now
                </button>
                <p class="mt-4 text-center text-xs text-gray-500">
                    By placing your order, you agree to our Terms of Service and Privacy Policy.
                </p>
            </div>
{{-- Delete Confirmation Modal --}}
<div x-show="openDeleteCheckout" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity" @click="openDeleteCheckout = false"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div x-show="openDeleteCheckout"
             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-sm border border-zinc-100"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-rose-600"></div>

            <button type="button" 
                    @click="openDeleteCheckout = false" 
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
                        <h3 class="text-lg font-bold text-zinc-900 leading-6">Remove Item</h3>
                        <p class="mt-1 text-sm text-zinc-500">
                            Are you sure you want to remove <span class="font-semibold text-zinc-700" x-text="itemToDeleteCheckout?.name"></span> from this order?
                        </p>
                    </div>
                </div>

                <div class="mt-7 flex flex-col sm:flex-row-reverse gap-3 border-t border-zinc-100 pt-5">
                    <button type="button" 
                            @click="removeCheckoutItem()"
                            class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-red-700 focus:outline-none transition-all active:scale-98">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Remove
                    </button>
                    <button type="button" 
                            @click="openDeleteCheckout = false" 
                            class="flex-1 w-full rounded-xl bg-zinc-100 px-4 py-3 text-sm font-bold text-zinc-700 hover:bg-zinc-200 focus:outline-none transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </form>
</div>
@endsection
