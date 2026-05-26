@extends('layouts.vendor-main')

@section('title', 'Add Product')

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10" x-data="{ step: 1, selectedFiles: [] }">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900">
            Add Product
        </h1>
        <p class="mt-2 text-sm text-zinc-500">
            Fill in the details below to launch your new product on the catalog.
        </p>
    </div>

    {{-- Step progress bar --}}
    <div class="mb-10 max-w-xl mx-auto">
        <div class="relative flex items-center justify-between">
            <!-- Background track -->
            <div class="absolute left-0 top-1/2 h-1 w-full -translate-y-1/2 bg-zinc-100 rounded-full"></div>
            <!-- Progress track -->
            <div class="absolute left-0 top-1/2 h-1 -translate-y-1/2 bg-emerald-600 rounded-full transition-all duration-300"
                 :style="'width: ' + ((step - 1) * 50) + '%'"></div>

            <!-- Step 1 -->
            <button type="button" @click="step = 1" class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-300 font-bold text-sm"
                 :class="step >= 1 ? 'border-emerald-600 bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'border-zinc-200 bg-white text-zinc-400'">
                1
            </button>

            <!-- Step 2 -->
            <button type="button" @click="step = 2" class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-300 font-bold text-sm"
                 :class="step >= 2 ? 'border-emerald-600 bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'border-zinc-200 bg-white text-zinc-400'">
                2
            </button>

            <!-- Step 3 -->
            <button type="button" @click="step = 3" class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-300 font-bold text-sm"
                 :class="step >= 3 ? 'border-emerald-600 bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'border-zinc-200 bg-white text-zinc-400'">
                3
            </button>
        </div>
        <div class="mt-4 flex justify-between text-xs font-bold text-zinc-400 select-none">
            <span :class="step >= 1 ? 'text-emerald-700' : ''">Basic Details</span>
            <span :class="step >= 2 ? 'text-emerald-700' : ''" class="text-center">Pricing & Units</span>
            <span :class="step >= 3 ? 'text-emerald-700' : ''" class="text-right">Status & Media</span>
        </div>
    </div>

    {{-- Global Success & Error Notification Banners --}}
    @if(session('success'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200/60 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" />
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200/60 p-4 text-sm font-semibold text-rose-800 shadow-sm">
            <x-heroicon-o-x-circle class="h-5 w-5 text-rose-600" />
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-8 rounded-2xl bg-amber-50 border border-amber-200/60 p-5 text-sm text-amber-800 shadow-sm">
            <div class="flex items-center gap-2.5 font-bold mb-2">
                <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-amber-600" />
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('vendor.items.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
        @csrf

        {{-- STEP 1: BASIC INFORMATION --}}
        <div x-show="step === 1" x-transition class="space-y-8">
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-zinc-955 flex items-center gap-2 border-b border-zinc-50 pb-4">
                    <x-heroicon-o-document-text class="h-5 w-5 text-emerald-600" />
                    Basic Information
                </h2>

                <div class="space-y-6">
                    {{-- Product Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter product name"
                            class="w-full rounded-xl border @error('name') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            required
                        >
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-zinc-700">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Provide a clear, engaging product description..."
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >{{ old('description') }}</textarea>
                    </div>

                    {{-- Categories --}}
                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700">Categories</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2.5 rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 cursor-pointer transition-all duration-200 hover:border-emerald-300 hover:bg-emerald-50/50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500/20">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $cat->category_id }}"
                                        {{ (collect(old('categories', []))->contains($cat->category_id)) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500/30 transition"
                                    >
                                    <span class="text-sm font-medium text-zinc-700">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Select one or more categories the product belongs to.</p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Brand --}}
                        <div>
                            <label for="brand" class="mb-2 block text-sm font-semibold text-zinc-700">Brand</label>
                            <input
                                type="text"
                                id="brand"
                                name="brand"
                                value="{{ old('brand') }}"
                                placeholder="Enter brand name"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            >
                        </div>

                        {{-- SKU --}}
                        <div>
                            <label for="sku" class="mb-2 block text-sm font-semibold text-zinc-700">SKU</label>
                            <input
                                type="text"
                                id="sku"
                                name="sku"
                                value="{{ old('sku') }}"
                                placeholder="Enter SKU"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            >
                        </div>
                    </div>

                    {{-- Barcode --}}
                    <div>
                        <label for="barcode" class="mb-2 block text-sm font-semibold text-zinc-700">Barcode</label>
                        <input
                            type="text"
                            id="barcode"
                            name="barcode"
                            value="{{ old('barcode') }}"
                            placeholder="Enter barcode value"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >
                    </div>
                </div>
            </div>

            {{-- FORM ACTIONS STEP 1 --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a
                    href="{{ route('vendor.products') }}"
                    class="flex-1 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-center text-sm font-semibold text-zinc-700 transition duration-200 hover:bg-zinc-50 shadow-sm"
                >
                    Cancel
                </a>
                <button
                    type="button"
                    @click="step = 2"
                    class="flex-1 rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center justify-center gap-2"
                >
                    Next Step
                    <x-heroicon-s-arrow-right class="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- STEP 2: PRICING, STOCK & UNITS --}}
        <div x-show="step === 2" x-transition class="space-y-8" style="display: none;">
            <div class="grid gap-8 md:grid-cols-2">
                
                {{-- PRICING & INVENTORY --}}
                <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-955 flex items-center gap-2 border-b border-zinc-50 pb-4">
                        <x-heroicon-o-banknotes class="h-5 w-5 text-emerald-600" />
                        Pricing & Inventory
                    </h3>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Price <span class="text-rose-500">*</span>
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
                                value="{{ old('price') }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border @error('price') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 pl-9 pr-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            >
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div>
                        <label for="stock" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Initial Stock <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="stock"
                            name="stock"
                            value="{{ old('stock') }}"
                            placeholder="0"
                            class="w-full rounded-xl border @error('stock') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >
                    </div>
                </div>

                {{-- UNIT CONFIGURATION --}}
                <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-zinc-955 flex items-center gap-2 border-b border-zinc-50 pb-4">
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
                            value="{{ old('unit_value') }}"
                            placeholder="1.00"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >
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
                            <option value="">Select Unit Type</option>
                            @foreach(['piece' => 'Piece', 'pack' => 'Pack', 'box' => 'Box', 'cup' => 'Cup', 'kg' => 'Kilogram (kg)', 'g' => 'Gram (g)', 'liter' => 'Liter (L)', 'ml' => 'Milliliter (ml)'] as $val => $label)
                                <option value="{{ $val }}" {{ old('unit_type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            {{-- FORM ACTIONS STEP 2 --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button
                    type="button"
                    @click="step = 1"
                    class="flex-1 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-center text-sm font-semibold text-zinc-700 transition duration-200 hover:bg-zinc-50 shadow-sm flex items-center justify-center gap-2"
                >
                    <x-heroicon-s-arrow-left class="h-5 w-5" />
                    Back
                </button>
                <button
                    type="button"
                    @click="step = 3"
                    class="flex-1 rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center justify-center gap-2"
                >
                    Next Step
                    <x-heroicon-s-arrow-right class="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- STEP 3: VISIBILITY & MEDIA --}}
        <div x-show="step === 3" x-transition class="space-y-8" style="display: none;">
            {{-- PRODUCT SETTINGS --}}
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-50 pb-4">
                    <x-heroicon-o-cog-6-tooth class="h-5 w-5 text-emerald-600" />
                    Product Settings
                </h2>

                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Active --}}
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Active Product</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Enable and keep this product registered inside the system.</p>
                        </div>
                    </label>

                    {{-- Available --}}
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_available"
                            value="1"
                            {{ old('is_available', '1') == '1' ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Available for Purchase</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Let customers discover and checkout this item in the store list.</p>
                        </div>
                    </label>

                    {{-- Bundle --}}
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_bundle"
                            value="1"
                            {{ old('is_bundle') == '1' ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Bundle Product</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Check this if the item is packaged together with multiple other goods.</p>
                        </div>
                    </label>

                    {{-- Perishable --}}
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_perishable"
                            value="1"
                            {{ old('is_perishable') == '1' ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Perishable Product</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Indicates fresh produce or items that can expire, spoil, or go stale.</p>
                        </div>
                    </label>

                    {{-- Expiry --}}
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer md:col-span-2">
                        <input
                            type="checkbox"
                            name="has_expiry"
                            value="1"
                            {{ old('has_expiry') == '1' ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Has Expiry Date</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Product catalog will include barcode validation and active expiration tracking.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- PRODUCT IMAGE --}}
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-zinc-955 flex items-center gap-2 border-b border-zinc-50 pb-4">
                    <x-heroicon-o-camera class="h-5 w-5 text-emerald-600" />
                    Product Media
                </h2>

                <div>
                    <label for="images" class="mb-3 block text-sm font-semibold text-zinc-700">
                        Upload Product Images
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-zinc-200 border-dashed rounded-2xl cursor-pointer bg-zinc-50/30 hover:bg-zinc-50 transition-all duration-200 hover:border-emerald-500/30">
                            <template x-if="selectedFiles.length === 0">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <x-heroicon-o-cloud-arrow-up class="w-10 h-10 mb-3 text-zinc-400" />
                                    <p class="mb-2 text-sm text-zinc-600 font-semibold">
                                        Click to select images
                                    </p>
                                    <p class="text-xs text-zinc-400">
                                        JPEG, PNG, JPG or GIF (Max 2MB per file)
                                    </p>
                                </div>
                            </template>
                            <template x-if="selectedFiles.length > 0">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                    <x-heroicon-o-check-circle class="w-10 h-10 mb-3 text-emerald-500" />
                                    <p class="mb-1 text-sm text-zinc-700 font-semibold" x-text="selectedFiles.length + ' file(s) selected'"></p>
                                    <p class="text-xs text-zinc-400 truncate max-w-full" x-text="[...selectedFiles].map(f => f.name).join(', ')"></p>
                                </div>
                            </template>
                            <input
                                type="file"
                                id="images"
                                name="images[]"
                                accept="image/*"
                                multiple
                                class="hidden"
                                @change="selectedFiles = $event.target.files"
                            >
                        </label>
                    </div>
                </div>
            </div>

            {{-- FORM ACTIONS STEP 3 --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button
                    type="button"
                    @click="step = 2"
                    class="flex-1 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-center text-sm font-semibold text-zinc-700 transition duration-200 hover:bg-zinc-50 shadow-sm flex items-center justify-center gap-2"
                >
                    <x-heroicon-s-arrow-left class="h-5 w-5" />
                    Back
                </button>
                <button
                    type="submit"
                    class="flex-1 rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white transition duration-200 hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-[0.99] flex items-center justify-center gap-2"
                >
                    <x-heroicon-s-check-circle class="h-5 w-5" />
                    Add New Product
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
