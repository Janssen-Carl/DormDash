@extends('layouts.vendor-main')

@section('title', 'Add Item')

@section('content')
    <div class="mx-auto max-w-7xl px-8 py-12">

        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-zinc-900">Add Item</h1>
            <p class="mt-2 text-zinc-500 text-sm">
                Fill in the details for the new inventory item.
            </p>
        </div>

        <form method="POST" action="{{ route('vendor.items.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg space-y-6">

                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Item Name</label>
                    <input type="text" name="name"
                           class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3"
                           required>
                </div>

                {{-- DESCRIPTION --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3"></textarea>
                </div>

                {{-- PRICE + STOCK --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price</label>
                        <input type="number" step="0.01" name="price"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stock</label>
                        <input type="number" name="stock"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3"
                               required>
                    </div>
                </div>

                {{-- SKU / BRAND --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">SKU</label>
                        <input type="text" name="sku"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Brand</label>
                        <input type="text" name="brand"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                    </div>
                </div>

                {{-- BARCODE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Barcode</label>
                    <input type="text" name="barcode"
                           class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                </div>

                {{-- UNIT TYPE --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Type</label>
                        <input type="text" name="unit_type"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3"
                               placeholder="kg, pcs, box">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Value</label>
                        <input type="number" step="0.01" name="unit_value"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                    </div>
                </div>

                {{-- FLAGS --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bundle</label>
                        <select name="is_bundle"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Available</label>
                        <select name="is_available"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Perishable</label>
                        <select name="is_perishable"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Has Expiry</label>
                        <select name="has_expiry"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-4">
                <a href="{{ url()->previous() }}"
                   class="px-10 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">
                    Cancel
                </a>

                <button type="submit"
                        class="px-10 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    Add Item
                </button>
            </div>

        </form>
    </div>
@endsection
