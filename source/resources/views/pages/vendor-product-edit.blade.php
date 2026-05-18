@extends('layouts.vendor-main')

@section('title', 'Edit Product')

@section('content')
<div 
    class="mx-auto max-w-6xl px-6 py-10"
    x-data="{ showPhotoModal: false }"
>

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900">
            Edit Product
        </h1>

        <p class="mt-2 text-sm text-zinc-500">
            Update the product details below.
        </p>
    </div>

    <form action="" method="POST" class="space-y-8">
        @csrf

        {{-- ============================= --}}
        {{-- BASIC INFORMATION --}}
        {{-- ============================= --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 class="mb-8 text-lg font-bold text-gray-900">
                Basic Information
            </h2>

            <div class="space-y-6">

                {{-- Product Name --}}
                <div>
                    <label 
                        for="name"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Product Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter product name"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label 
                        for="description"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Enter product description"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    ></textarea>
                </div>

                <div class="grid gap-6 md:grid-cols-2">

                    {{-- Brand --}}
                    <div>
                        <label 
                            for="brand"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Brand
                        </label>

                        <input
                            type="text"
                            id="brand"
                            name="brand"
                            placeholder="Enter brand name"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                        >
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label 
                            for="sku"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            SKU
                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            placeholder="Enter SKU"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                        >
                    </div>

                </div>

                {{-- Barcode --}}
                <div>
                    <label 
                        for="barcode"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Barcode
                    </label>

                    <input
                        type="text"
                        id="barcode"
                        name="barcode"
                        placeholder="Enter barcode"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

            </div>
        </div>

        {{-- ============================= --}}
        {{-- PRICING & INVENTORY --}}
        {{-- ============================= --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 class="mb-8 text-lg font-bold text-gray-900">
                Pricing & Inventory
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                {{-- Price --}}
                <div>
                    <label 
                        for="price"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        id="price"
                        name="price"
                        placeholder="₱0.00"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                {{-- Stock --}}
                <div>
                    <label 
                        for="stock"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Stock
                    </label>

                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        placeholder="0"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                {{-- Unit Type --}}
                <div>
                    <label 
                        for="unit_type"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Unit Type
                    </label>

                    <select
                        id="unit_type"
                        name="unit_type"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="">Select Unit</option>
                        <option value="piece">Piece</option>
                        <option value="kg">Kilogram</option>
                        <option value="g">Gram</option>
                        <option value="liter">Liter</option>
                        <option value="ml">Milliliter</option>
                        <option value="pack">Pack</option>
                        <option value="box">Box</option>
                    </select>
                </div>

                {{-- Unit Value --}}
                <div>
                    <label 
                        for="unit_value"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Unit Value
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        id="unit_value"
                        name="unit_value"
                        placeholder="1"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

            </div>
        </div>

        {{-- ============================= --}}
        {{-- PRODUCT SETTINGS --}}
        {{-- ============================= --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 class="mb-8 text-lg font-bold text-gray-900">
                Product Settings
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                {{-- Active --}}
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        checked
                        class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            Active Product
                        </p>

                        <p class="text-xs text-gray-500">
                            Product is enabled in the system
                        </p>
                    </div>
                </label>

                {{-- Available --}}
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4">
                    <input
                        type="checkbox"
                        name="is_available"
                        checked
                        class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            Available for Purchase
                        </p>

                        <p class="text-xs text-gray-500">
                            Customers can buy this item
                        </p>
                    </div>
                </label>

                {{-- Bundle --}}
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4">
                    <input
                        type="checkbox"
                        name="is_bundle"
                        class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            Bundle Product
                        </p>

                        <p class="text-xs text-gray-500">
                            Product contains multiple items
                        </p>
                    </div>
                </label>

                {{-- Perishable --}}
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4">
                    <input
                        type="checkbox"
                        name="is_perishable"
                        class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            Perishable Product
                        </p>

                        <p class="text-xs text-gray-500">
                            Product may expire or spoil
                        </p>
                    </div>
                </label>

                {{-- Expiry --}}
                <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4 md:col-span-2">
                    <input
                        type="checkbox"
                        name="has_expiry"
                        class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            Has Expiry Date
                        </p>

                        <p class="text-xs text-gray-500">
                            Product includes expiration tracking
                        </p>
                    </div>
                </label>

            </div>
        </div>

        {{-- ============================= --}}
        {{-- PRODUCT IMAGE --}}
        {{-- ============================= --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Product Image
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Upload a clean product image.
                    </p>
                </div>

                <button
                    type="button"
                    @click="showPhotoModal = true"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    <x-heroicon-o-camera class="h-5 w-5" />
                    Add Image
                </button>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex gap-4">
            <a
                href="/vendor-products"
                class="flex-1 rounded-xl border-2 border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-800 transition hover:bg-gray-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="flex-1 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
            >
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-check class="h-4 w-4" />
                    Save Changes
                </span>
            </button>
        </div>

    </form>
</div>
@endsection