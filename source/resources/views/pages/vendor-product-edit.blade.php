@extends('layouts.vendor-main')

@section('title', 'Edit Product')

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900">
            Edit Product
        </h1>
        <p class="mt-2 text-sm text-zinc-500">
            Update your product's inventory, price, unit specifications, and availability.
        </p>
    </div>

    {{-- Read-Only Product Summary Card --}}
    <div class="mb-8 rounded-3xl border border-zinc-100 bg-white p-6 shadow-sm flex flex-col sm:flex-row items-center gap-6">
        <div class="flex h-24 w-24 shrink-0 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shadow-inner">
            @if ($item->images->first())
                <img src="{{ asset($item->images->first()->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
            @else
                <div class="flex h-full w-full items-center justify-center text-zinc-300">
                    <x-heroicon-o-photo class="h-10 w-10" />
                </div>
            @endif
        </div>
        <div class="text-center sm:text-left flex-1 min-w-0">
            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-800">
                {{ $item->brand ?? 'No Brand' }}
            </span>
            <h2 class="mt-2 text-xl font-bold text-zinc-900 truncate" title="{{ $item->name }}">
                {{ $item->name }}
            </h2>
            <p class="mt-1 text-xs text-zinc-400">
                SKU: <span class="font-mono text-zinc-600">{{ $item->sku ?? 'N/A' }}</span>
            </p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('vendor.products.update', $item->item_id) }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid gap-8 md:grid-cols-2">
            
            {{-- PRICING & STOCK --}}
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-zinc-955 flex items-center gap-2">
                    <x-heroicon-o-banknotes class="h-5 w-5 text-emerald-600" />
                    Pricing & Inventory
                </h3>

                {{-- Price --}}
                <div>
                    <label for="price" class="mb-2 block text-sm font-semibold text-zinc-700">
                        Price (PHP)
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <span class="text-zinc-500 sm:text-sm">₱</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            id="price"
                            name="price"
                            value="{{ old('price', $item->price) }}"
                            placeholder="0.00"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 pl-9 pr-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            required
                        >
                    </div>
                    @error('price')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock" class="mb-2 block text-sm font-semibold text-zinc-700">
                        Current Stock Level
                    </label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $item->stock) }}"
                        placeholder="0"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        required
                    >
                    @error('stock')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- UNIT CONFIGURATION --}}
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2">
                    <x-heroicon-o-scale class="h-5 w-5 text-emerald-600" />
                    Unit Specifications
                </h3>

                {{-- Unit Value --}}
                <div>
                    <label for="unit_value" class="mb-2 block text-sm font-semibold text-zinc-700">
                        Unit Value (e.g. 1.00, 250, 500)
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        id="unit_value"
                        name="unit_value"
                        value="{{ old('unit_value', $item->unit_value) }}"
                        placeholder="1.00"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                    >
                    @error('unit_value')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unit Type --}}
                <div>
                    <label for="unit_type" class="mb-2 block text-sm font-semibold text-zinc-700">
                        Unit Type
                    </label>
                    <select
                        id="unit_type"
                        name="unit_type"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                    >
                        <option value="" {{ old('unit_type', $item->unit_type) == '' ? 'selected' : '' }}>Select Unit Type</option>
                        <option value="piece" {{ old('unit_type', $item->unit_type) == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="pack" {{ old('unit_type', $item->unit_type) == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="box" {{ old('unit_type', $item->unit_type) == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="cup" {{ old('unit_type', $item->unit_type) == 'cup' ? 'selected' : '' }}>Cup</option>
                        <option value="kg" {{ old('unit_type', $item->unit_type) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ old('unit_type', $item->unit_type) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="liter" {{ old('unit_type', $item->unit_type) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                        <option value="ml" {{ old('unit_type', $item->unit_type) == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                    </select>
                    @error('unit_type')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        {{-- STATUS SETTINGS --}}
        <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm">
            <h3 class="text-lg font-bold text-zinc-955 flex items-center gap-2 mb-6">
                <x-heroicon-o-eye class="h-5 w-5 text-emerald-600" />
                Product Status
            </h3>

            <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                <input
                    type="checkbox"
                    name="is_available"
                    value="1"
                    {{ old('is_available', $item->is_available) ? 'checked' : '' }}
                    class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                >
                <div class="select-none">
                    <p class="text-sm font-bold text-zinc-900">
                        Available for Purchase
                    </p>
                    <p class="text-xs text-zinc-500 mt-0.5">
                        If checked, this item will be immediately visible and purchasable by customers on the store catalog.
                    </p>
                </div>
            </label>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a
                href="{{ route('vendor.products') }}"
                class="flex-1 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-center text-sm font-semibold text-zinc-700 transition duration-200 hover:bg-zinc-50 shadow-sm"
            >
                Cancel
            </a>
            <button
                type="submit"
                class="flex-1 rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center justify-center gap-2"
            >
                <x-heroicon-s-check-circle class="h-5 w-5" />
                Save Product Changes
            </button>
        </div>

    </form>
</div>
@endsection