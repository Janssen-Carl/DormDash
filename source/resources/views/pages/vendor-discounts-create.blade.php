@extends('layouts.vendor-main')

@section('title', 'Create Discount - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10" x-data="{
    step: {{ isset($selectedItemId) ? 2 : 1 }},
    itemId: '{{ old('item_id', $selectedItemId ?? '') }}',
    name: '{{ old('name', '') }}',
    type: '{{ old('type', 'percentage') }}',
    value: '{{ old('value', '') }}',
    dateStart: '{{ old('date_start', now()->format('Y-m-d\TH:i')) }}',
    dateEnd: '{{ old('date_end', now()->addDays(7)->format('Y-m-d\TH:i')) }}',
    isActive: true,
    productSearch: '',
    itemsList: {
        @foreach($items as $item)
            '{{ $item->item_id }}': {
                item_id: '{{ $item->item_id }}',
                name: '{{ addslashes($item->name) }}',
                price: {{ $item->price }},
                stock: {{ $item->stock }},
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
    },
    get filteredProducts() {
        let q = this.productSearch.toLowerCase();
        return Object.values(this.itemsList).filter(item => {
            return item.name.toLowerCase().includes(q);
        });
    }
}">

    {{-- Header --}}
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('vendor.discounts') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-zinc-200 text-zinc-600 transition hover:bg-zinc-50 hover:text-zinc-900">
                <x-heroicon-o-chevron-left class="h-5 w-5" />
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900">
                    Create Promotion
                </h1>
                <p class="mt-1 text-sm text-zinc-500">
                    Design a new campaign, set validity dates, and offer attractive discounts for your customers.
                </p>
            </div>
        </div>
        
        {{-- Floating Reset Wizard option --}}
        <template x-if="itemId">
            <button 
                type="button" 
                @click="itemId = ''; step = 1; name = ''; value = '';"
                class="inline-flex items-center gap-1.5 rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-xs font-bold text-zinc-600 hover:bg-zinc-50 transition shadow-sm"
            >
                <x-heroicon-o-arrow-path class="h-4 w-4" />
                Reset Wizard
            </button>
        </template>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Form Column --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Step Indicator Header --}}
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex flex-wrap items-center gap-6">
                        <!-- Step 1 Button -->
                        <button 
                            type="button" 
                            @click="step = 1" 
                            class="flex items-center gap-2.5 text-left select-none outline-none group"
                        >
                            <span 
                                class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold transition-all duration-300 border"
                                :class="step === 1 ? 'bg-emerald-600 text-white border-emerald-600 ring-4 ring-emerald-500/10' : (itemId ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-zinc-100 text-zinc-400 border-zinc-200')"
                            >
                                <template x-if="itemId && step > 1">
                                    <x-heroicon-s-check class="h-4 w-4" />
                                </template>
                                <template x-if="step === 1 || !itemId">
                                    <span>1</span>
                                </template>
                            </span>
                            <div>
                                <span class="block text-xs font-extrabold uppercase tracking-wider text-zinc-400">Step 1</span>
                                <span class="text-sm font-bold transition-colors" :class="step === 1 ? 'text-zinc-900' : 'text-zinc-500 group-hover:text-zinc-800'">Select Product</span>
                            </div>
                        </button>

                        <x-heroicon-o-chevron-right class="hidden sm:block h-5 w-5 text-zinc-300" />

                        <!-- Step 2 Button -->
                        <button 
                            type="button" 
                            @click="if(itemId) step = 2" 
                            :disabled="!itemId"
                            class="flex items-center gap-2.5 text-left select-none outline-none group disabled:opacity-50"
                        >
                            <span 
                                class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold transition-all duration-300 border"
                                :class="step === 2 ? 'bg-emerald-600 text-white border-emerald-600 ring-4 ring-emerald-500/10' : (step > 2 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-zinc-100 text-zinc-400 border-zinc-200')"
                            >
                                <template x-if="name && value && step > 2">
                                    <x-heroicon-s-check class="h-4 w-4" />
                                </template>
                                <template x-if="step <= 2 || !name || !value">
                                    <span>2</span>
                                </template>
                            </span>
                            <div>
                                <span class="block text-xs font-extrabold uppercase tracking-wider text-zinc-400">Step 2</span>
                                <span class="text-sm font-bold transition-colors" :class="step === 2 ? 'text-zinc-900' : 'text-zinc-500 group-hover:text-zinc-800'">Campaign Details</span>
                            </div>
                        </button>

                        <x-heroicon-o-chevron-right class="hidden sm:block h-5 w-5 text-zinc-300" />

                        <!-- Step 3 Button -->
                        <button 
                            type="button" 
                            @click="if(itemId && name && value) step = 3" 
                            :disabled="!itemId || !name || !value"
                            class="flex items-center gap-2.5 text-left select-none outline-none group disabled:opacity-50"
                        >
                            <span 
                                class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold transition-all duration-300 border border-zinc-200"
                                :class="step === 3 ? 'bg-emerald-600 text-white border-emerald-600 ring-4 ring-emerald-500/10' : 'bg-zinc-100 text-zinc-400'"
                            >
                                3
                            </span>
                            <div>
                                <span class="block text-xs font-extrabold uppercase tracking-wider text-zinc-400">Step 3</span>
                                <span class="text-sm font-bold transition-colors" :class="step === 3 ? 'text-zinc-900' : 'text-zinc-500 group-hover:text-zinc-800'">Schedule & Limits</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <form action="{{ route('vendor.discounts.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Hidden Fields for standard form submission --}}
                <input type="hidden" name="item_id" :value="itemId" required>

                {{-- STEP 1: SELECT PRODUCT CARD --}}
                <div x-show="step === 1" x-transition class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
                        <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2">
                            <x-heroicon-o-shopping-bag class="h-5 w-5 text-emerald-600" />
                            Select Product for Discount
                        </h3>
                        <span class="text-xs text-zinc-400 font-semibold" x-text="Object.keys(itemsList).length + ' products available'"></span>
                    </div>

                    {{-- Product List Search input --}}
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </span>
                        <input
                            type="text"
                            x-model="productSearch"
                            placeholder="Filter products by name..."
                            class="w-full rounded-2xl border border-zinc-200 bg-zinc-50 pl-11 pr-4 py-3 text-sm text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 placeholder-zinc-400 font-semibold"
                        >
                    </div>

                    {{-- Scrollable Product Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[380px] overflow-y-auto pr-2">
                        <template x-for="item in filteredProducts" :key="item.item_id">
                            <div 
                                @click="itemId = item.item_id; step = 2;" 
                                class="flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none hover:scale-[1.01]"
                                :class="itemId === item.item_id ? 'border-emerald-500 bg-emerald-50/20 ring-2 ring-emerald-500/10' : 'border-zinc-200 bg-white hover:border-zinc-300 hover:shadow-sm'"
                            >
                                <div class="h-12 w-12 rounded-xl overflow-hidden bg-zinc-50 border border-zinc-100 flex items-center justify-center shrink-0">
                                    <template x-if="item.image">
                                        <img :src="item.image" class="h-full w-full object-cover" />
                                    </template>
                                    <template x-if="!item.image">
                                        <x-heroicon-o-photo class="h-6 w-6 text-zinc-300" />
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-zinc-900 text-sm truncate" x-text="item.name"></h4>
                                    <p class="text-xs text-zinc-400 mt-0.5">Price: ₱<span x-text="item.price.toFixed(2)"></span> · Stock: <span x-text="item.stock"></span></p>
                                </div>
                                <div class="h-5.5 w-5.5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                     :class="itemId === item.item_id ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-zinc-300 bg-white'">
                                     <template x-if="itemId === item.item_id">
                                         <x-heroicon-s-check class="h-3 w-3" />
                                     </template>
                                </div>
                            </div>
                        </template>

                        {{-- Empty Search fallback --}}
                        <template x-if="filteredProducts.length === 0">
                            <div class="col-span-2 py-12 text-center text-zinc-400">
                                <p class="text-sm font-bold">No products match your filter query</p>
                                <p class="text-xs mt-1">Try refining your keyword search.</p>
                            </div>
                        </template>
                    </div>

                    {{-- Next Step Actions --}}
                    <div class="pt-4 border-t border-zinc-100 flex justify-end">
                        <button
                            type="button"
                            @click="step = 2"
                            :disabled="!itemId"
                            class="rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5 shadow-md shadow-emerald-600/10"
                        >
                            Next: Campaign Details
                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                {{-- STEP 2: CAMPAIGN DETAILS CARD --}}
                <div x-show="step === 2" x-transition class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-100 pb-4">
                        <x-heroicon-o-sparkles class="h-5 w-5 text-emerald-600" />
                        Campaign Details
                    </h3>

                    {{-- Selected Product Indicator --}}
                    <template x-if="currentItem">
                        <div class="flex items-center justify-between bg-zinc-50 border border-zinc-200 rounded-2xl p-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl overflow-hidden bg-white border shrink-0 flex items-center justify-center">
                                    <template x-if="currentItem.image">
                                        <img :src="currentItem.image" class="h-full w-full object-cover" />
                                    </template>
                                    <template x-if="!currentItem.image">
                                        <x-heroicon-o-photo class="h-5 w-5 text-zinc-300" />
                                    </template>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Target Product</span>
                                    <span class="text-sm font-extrabold text-zinc-900" x-text="currentItem.name"></span>
                                </div>
                            </div>
                            <button type="button" @click="step = 1" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Change</button>
                        </div>
                    </template>

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
                            placeholder="e.g. Summer Sale, Midnight Special"
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
                        >{{ old('description') }}</textarea>
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

                    {{-- Wizard Actions --}}
                    <div class="pt-4 border-t border-zinc-100 flex items-center justify-between gap-4">
                        <button
                            type="button"
                            @click="step = 1"
                            class="rounded-2xl border border-zinc-200 bg-white px-6 py-3.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50 shadow-sm"
                        >
                            Back
                        </button>
                        <button
                            type="button"
                            @click="step = 3"
                            :disabled="!name || !value || parseFloat(value) <= 0"
                            class="rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5 shadow-md shadow-emerald-600/10"
                        >
                            Next: Schedule
                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                {{-- STEP 3: SCHEDULE & LIMITS CARD --}}
                <div x-show="step === 3" x-transition class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-100 pb-4">
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
                                value="{{ old('use_limit') }}"
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
                                    <span class="text-sm font-bold text-zinc-900">Activate Campaign Now</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="pt-4 border-t border-zinc-100 flex items-center justify-between gap-4">
                        <button
                            type="button"
                            @click="step = 2"
                            class="rounded-2xl border border-zinc-200 bg-white px-6 py-3.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50 shadow-sm"
                        >
                            Back
                        </button>
                        <button
                            type="submit"
                            class="rounded-2xl bg-emerald-600 px-8 py-3.5 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center gap-2"
                        >
                            <x-heroicon-s-check-circle class="h-5 w-5" />
                            Launch Promotion
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- Preview Column --}}
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                
                {{-- Preview Header --}}
                <!-- <div class="rounded-3xl border border-zinc-200 bg-zinc-50 p-6">
                    <div class="flex items-center gap-2 text-zinc-800 mb-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold uppercase tracking-wider">Live Customer Preview</span>
                    </div>
                    <p class="text-xs text-zinc-500">See exactly how your new promotional campaign card will render on the store product listings page.</p>
                </div> -->

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
