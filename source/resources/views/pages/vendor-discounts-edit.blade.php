@extends('layouts.vendor-main')

@section('title', 'Edit Discount - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10" x-data="{
    itemId: '{{ old('item_id', $discount->item_id) }}',
    name: '{{ old('name', $discount->name) }}',
    type: '{{ old('type', $discount->type) }}',
    value: '{{ old('value', $discount->value) }}',
    dateStart: '{{ old('date_start', \Carbon\Carbon::parse($discount->date_start)->format('Y-m-d\TH:i')) }}',
    dateEnd: '{{ old('date_end', \Carbon\Carbon::parse($discount->date_end)->format('Y-m-d\TH:i')) }}',
    isActive: {{ $discount->is_active ? 'true' : 'false' }},
    itemsList: {
        @foreach($items as $item)
            '{{ $item->item_id }}': {
                name: '{{ addslashes($item->name) }}',
                price: {{ $item->price }},
                image: '{{ $item->images->first() ? asset($item->images->first()->image) : '' }}'
            },
        @endforeach
    },
    get currentItem() {
        return this.itemsList[this.itemId] || null;
    },
    get promoPrice() {
        if (!this.currentItem) return 0;
        let base = this.currentItem.price;
        let val = parseFloat(this.value) || 0;
        if (this.type === 'percentage') {
            return Math.max(0, base - (base * (val / 100)));
        }
        return Math.max(0, base - val);
    }
}">

    {{-- Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('vendor.discounts') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50 hover:text-zinc-900">
                <x-heroicon-o-chevron-left class="h-5 w-5" />
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900">
                    Edit Promotion
                </h1>
                <p class="mt-1 text-sm text-zinc-500">
                    Update your promotional strategy, extend date boundaries, or toggle status of the campaign.
                </p>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Form Column --}}
        <div class="lg:col-span-2 space-y-8">
            <form action="{{ route('vendor.discounts.update', $discount->discount_id) }}" method="POST" class="space-y-8">
                @csrf

                {{-- Locked Selected Product Card --}}
                <div class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-100 pb-4">
                        <x-heroicon-o-shopping-bag class="h-5 w-5 text-emerald-600" />
                        Target Product
                    </h3>

                    <input type="hidden" name="item_id" value="{{ $discount->item_id }}" required>

                    <div class="flex items-center gap-4 bg-zinc-50 border border-zinc-200 rounded-2xl p-4.5">
                        <div class="h-14 w-14 rounded-xl overflow-hidden bg-white border shrink-0 flex items-center justify-center">
                            @if ($discount->item->images->first())
                                <img src="{{ asset($discount->item->images->first()->image) }}" alt="{{ $discount->item->name }}" class="h-full w-full object-cover" />
                            @else
                                <div class="text-zinc-300">
                                    <x-heroicon-o-photo class="h-6 w-6" />
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Promoted Item (Locked)</span>
                            <span class="text-base font-extrabold text-zinc-900 leading-snug">{{ $discount->item->name }}</span>
                            <span class="block text-xs text-zinc-500 mt-1">Base Price: ₱{{ number_format($discount->item->price, 2) }} · SKU: {{ $discount->item->sku ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Campaign Details Card --}}
                <div class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-100 pb-4">
                        <x-heroicon-o-sparkles class="h-5 w-5 text-emerald-600" />
                        Campaign Details
                    </h3>

                    {{-- Discount Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Campaign / Promotion Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            x-model="name"
                            placeholder="Enter promotion name..."
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-semibold"
                            required
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Short Description (Optional)
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Write a brief pitch for this discount..."
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >{{ old('description', $discount->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Type --}}
                        <div>
                            <label for="type" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Discount Type
                            </label>
                            <select
                                id="type"
                                name="type"
                                x-model="type"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-semibold"
                                required
                            >
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed PHP Value (₱)</option>
                            </select>
                            @error('type')
                                <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Value --}}
                        <div>
                            <label for="value" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Reduction Value
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                id="value"
                                name="value"
                                x-model="value"
                                placeholder="e.g. 10 for 10% or 15 for ₱15.00"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-semibold"
                                required
                            >
                            @error('value')
                                <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Schedule and Limits Card --}}
                <div class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-955 flex items-center gap-2 border-b border-zinc-100 pb-4">
                        <x-heroicon-o-calendar class="h-5 w-5 text-emerald-600" />
                        Schedule & Limits
                    </h3>

                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Date Start --}}
                        <div>
                            <label for="date_start" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Start Date & Time
                            </label>
                            <input
                                type="datetime-local"
                                id="date_start"
                                name="date_start"
                                x-model="dateStart"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                                required
                            >
                            @error('date_start')
                                <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date End --}}
                        <div>
                            <label for="date_end" class="mb-2 block text-sm font-semibold text-zinc-700">
                                End Date & Time
                            </label>
                            <input
                                type="datetime-local"
                                id="date_end"
                                name="date_end"
                                x-model="dateEnd"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                                required
                            >
                            @error('date_end')
                                <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Usage Limit --}}
                        <div>
                            <label for="use_limit" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Max Redemptions Limit (Optional)
                            </label>
                            <input
                                type="number"
                                id="use_limit"
                                name="use_limit"
                                value="{{ old('use_limit', $discount->use_limit) }}"
                                placeholder="Leave blank for unlimited..."
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            >
                            @error('use_limit')
                                <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active status --}}
                        <div class="flex flex-col justify-end">
                            <label class="flex items-center gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer h-[54px]">
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    x-model="isActive"
                                    class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0"
                                >
                                <div class="select-none">
                                    <span class="text-sm font-bold text-zinc-900">Campaign Active</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a
                        href="{{ route('vendor.discounts') }}"
                        class="flex-1 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-center text-sm font-semibold text-zinc-700 transition duration-200 hover:bg-zinc-50 shadow-sm"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="flex-1 rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center justify-center gap-2"
                    >
                        <x-heroicon-s-check-circle class="h-5 w-5" />
                        Save Changes
                    </button>
                </div>

            </form>
        </div>

        {{-- Preview Column --}}
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                
                {{-- Preview Header --}}
                <div class="rounded-3xl border border-zinc-200 bg-zinc-50 p-6">
                    <div class="flex items-center gap-2 text-zinc-800 mb-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider">Live Customer Preview</span>
                    </div>
                    <p class="text-xs text-zinc-500">See exactly how your edited promotion card will render on the store product listings page.</p>
                </div>

                {{-- Product Showcase Preview Widget --}}
                <div class="rounded-3xl border border-zinc-200 bg-white shadow-xl overflow-hidden p-6 space-y-6 transition-all duration-300">
                    <div class="relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-zinc-50 to-zinc-100 border border-zinc-100 flex items-center justify-center">
                        {{-- Image Preview --}}
                        <template x-if="currentItem && currentItem.image">
                            <img :src="currentItem.image" class="h-full w-full object-cover" />
                        </template>
                        <template x-if="!currentItem || !currentItem.image">
                            <div class="text-zinc-300">
                                <x-heroicon-o-photo class="h-20 w-20" />
                            </div>
                        </template>

                        {{-- Stacking Badges --}}
                        <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                            {{-- Sparkle Promo Badge --}}
                            <template x-if="value > 0">
                                <div class="bg-red-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-md tracking-wider">
                                    <span x-show="type === 'percentage'" x-text="parseFloat(value).toFixed(0) + '% OFF'"></span>
                                    <span x-show="type === 'fixed'" x-text="'₱' + parseFloat(value).toFixed(0) + ' OFF'"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Product Details Block --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">STOREFRONT ITEM</span>
                            <template x-if="isActive">
                                <span class="inline-flex items-center gap-1 rounded bg-emerald-50 border border-emerald-100 px-2 py-0.5 text-[9px] font-bold text-emerald-700 uppercase tracking-wider">Live</span>
                            </template>
                        </div>

                        {{-- Product Name --}}
                        <h4 class="text-lg font-extrabold text-zinc-900 leading-tight truncate" x-text="currentItem ? currentItem.name : 'Choose a product...'"></h4>

                        {{-- Campaign Title --}}
                        <template x-if="name.length > 0">
                            <div class="bg-emerald-50/50 border border-emerald-100/50 rounded-xl p-2.5 flex items-center gap-2">
                                <x-heroicon-o-sparkles class="h-4 w-4 text-emerald-600 shrink-0" />
                                <span class="text-xs font-semibold text-emerald-800 truncate" x-text="name"></span>
                            </div>
                        </template>

                        {{-- Pricing Preview Block --}}
                        <div class="pt-2 flex items-baseline gap-2.5">
                            <template x-if="currentItem && value > 0">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-black text-green-600" x-text="'₱' + parseFloat(promoPrice).toFixed(2)"></span>
                                    <span class="text-sm font-semibold text-zinc-400 line-through" x-text="'₱' + parseFloat(currentItem.price).toFixed(2)"></span>
                                </div>
                            </template>
                            <template x-if="!currentItem || value <= 0">
                                <span class="text-2xl font-black text-zinc-900" x-text="currentItem ? '₱' + parseFloat(currentItem.price).toFixed(2) : '₱0.00'"></span>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
