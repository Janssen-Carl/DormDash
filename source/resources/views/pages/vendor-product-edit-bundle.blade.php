@extends('layouts.vendor-main')

@section('title', 'Edit Product Bundle')

@section('content')
@php
    $productData = $products->mapWithKeys(fn($p) => [$p->item_id => ['stock' => (int)$p->stock]]);
@endphp
<div class="mx-auto max-w-4xl px-6 py-10" x-data="bundleEditor({{ Js::from($productData) }})">

    {{-- Header --}}
    <div class="mb-10 text-center flex flex-col sm:flex-row items-center justify-center gap-6">
        <div class="flex h-24 w-24 shrink-0 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shadow-inner">
            @if ($item->images->first())
                <img src="{{ asset($item->images->first()->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
            @else
                <div class="flex h-full w-full items-center justify-center text-zinc-300">
                    <x-heroicon-o-photo class="h-10 w-10" />
                </div>
            @endif
        </div>
        <div class="text-center sm:text-left min-w-0">
            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">
                Bundle Package
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 truncate" title="{{ $item->name }}">
                Edit {{ $item->name }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500">
                Update bundle details, adjust price, or modify the included products.
            </p>
        </div>
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
            <span :class="step >= 1 ? 'text-emerald-700' : ''">Basic Info</span>
            <span :class="step >= 2 ? 'text-emerald-700' : ''" class="text-center">Select Items</span>
            <span :class="step >= 3 ? 'text-emerald-700' : ''" class="text-right">Media & Status</span>
        </div>
    </div>

    {{-- Global Success & Error Notification Banners --}}
    @if(session('success'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200/60 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600" />
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->has('error'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200/60 p-4 text-sm font-semibold text-rose-800 shadow-sm">
            <x-heroicon-o-x-circle class="h-5 w-5 text-rose-600" />
            {{ session('error') ?? $errors->first('error') }}
        </div>
    @endif

    @if ($errors->any() && !$errors->has('error'))
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
    <form action="{{ route('vendor.products.updateBundle', $item->item_id) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
        @csrf

        {{-- STEP 1: BASIC INFORMATION --}}
        <div x-show="step === 1" x-transition class="space-y-8">
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-50 pb-4">
                    <x-heroicon-o-document-text class="h-5 w-5 text-emerald-600" />
                    Bundle Details
                </h2>

                <div class="space-y-6">
                    <div>
                        <label for="bundle_name" class="mb-2 block text-sm font-semibold text-zinc-700">
                            Bundle Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="bundle_name"
                            name="bundle_name"
                            value="{{ old('bundle_name', $item->name) }}"
                            placeholder="e.g. Back to School Starter Kit"
                            class="w-full rounded-xl border @error('bundle_name') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                            required
                        >
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-zinc-700">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Describe what is included in this bundle..."
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >{{ old('description', $item->description) }}</textarea>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="price" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Bundle Price <span class="text-rose-500">*</span>
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
                                    class="w-full rounded-xl border @error('price') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 pl-9 pr-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="mb-2 block text-sm font-semibold text-zinc-700">
                                Total Bundle Stock <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                x-model="bundleStock"
                                value="{{ old('stock', $item->stock) }}"
                                placeholder="0"
                                class="w-full rounded-xl border @error('stock') border-rose-400 @else border-zinc-200 @enderror bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                                required
                            >
                            <template x-if="maxBundles < bundleStock">
                                <div class="mt-3 flex items-start gap-2.5 rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs font-medium text-amber-800">
                                    <x-heroicon-o-exclamation-triangle class="h-4 w-4 shrink-0 mt-0.5 text-amber-500" />
                                    <span>
                                        Bundle stock (<span x-text="bundleStock"></span>) exceeds what child item stock can support. 
                                        Maximum sellable bundles based on current child item availability: <strong x-text="maxBundles"></strong>.
                                        Stock will be capped on save.
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label for="sku" class="mb-2 block text-sm font-semibold text-zinc-700">Bundle SKU</label>
                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            value="{{ old('sku', $item->sku) }}"
                            placeholder="Optional"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 font-medium"
                        >
                    </div>
                </div>
            </div>

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

        {{-- STEP 2: SELECT PRODUCTS --}}
        <div x-show="step === 2" x-transition class="space-y-8" style="display: none;">
            <div class="rounded-3xl border border-zinc-100 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="text-lg font-bold text-zinc-950 flex items-center gap-2">
                        <x-heroicon-o-squares-plus class="h-5 w-5 text-emerald-600" />
                        Select Items to Include
                    </h3>
                    <p class="text-sm text-zinc-500 mt-1">Check the products and specify how many units of each are included in this bundle.</p>
                </div>

                <div class="max-h-[500px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider w-16">Select</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Product Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider w-32">Qty to Include</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-zinc-200">
                            @forelse ($products as $product)
                            @php
                                $isIncluded = $bundleItems->has($product->item_id);
                                $qty = $isIncluded ? $bundleItems[$product->item_id]->pivot->quantity : 1;
                            @endphp
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        name="selected_products[{{ $product->item_id }}][selected]" 
                                        value="1" 
                                        x-model="selected[{{ $product->item_id }}].selected"
                                        class="h-5 w-5 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 transition cursor-pointer"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 shrink-0 rounded-lg overflow-hidden border border-zinc-200">
                                            <img src="{{ $product->firstImage() }}" class="h-full w-full object-cover">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-zinc-900">{{ $product->name }}</div>
                                            <div class="text-xs font-medium text-emerald-600">₱{{ number_format($product->price, 2) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-600">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="number" 
                                        name="selected_products[{{ $product->item_id }}][quantity]" 
                                        min="1" 
                                        max="{{ $product->stock }}"
                                        value="{{ old('selected_products.' . $product->item_id . '.quantity', $qty) }}"
                                        x-model.number="selected[{{ $product->item_id }}].qty"
                                        x-bind:disabled="!selected[{{ $product->item_id }}].selected"
                                        class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-800 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 disabled:opacity-50 disabled:bg-zinc-100"
                                    >
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm font-medium text-zinc-500">
                                    No active standalone products available. Please add standard products first.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
            <div class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-zinc-950 flex items-center gap-2 border-b border-zinc-50 pb-4">
                    <x-heroicon-o-eye class="h-5 w-5 text-emerald-600" />
                    Bundle Status
                </h2>

                <div>
                    <label class="flex items-start gap-4 rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-50/10 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                            class="h-5.5 w-5.5 rounded-lg border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 mt-0.5"
                        >
                        <div class="select-none text-left">
                            <p class="text-sm font-bold text-zinc-900">Active Bundle</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Publish this bundle immediately and make it available for purchase.</p>
                        </div>
                    </label>
                </div>
            </div>

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
                    Save Bundle Changes
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function bundleEditor(products) {
    return {
        step: 1,
        bundleStock: {{ $item->stock }},
        selected: {},
        init() {
            @foreach ($products as $product)
                @php $isIncluded = $bundleItems->has($product->item_id); @endphp
                this.selected[{{ $product->item_id }}] = {
                    selected: {{ $isIncluded ? 'true' : 'false' }},
                    qty: {{ $isIncluded ? $bundleItems[$product->item_id]->pivot->quantity : 1 }}
                };
            @endforeach
        },
        get maxBundles() {
            let max = Infinity;
            for (const [id, data] of Object.entries(this.selected)) {
                if (data.selected) {
                    const childStock = (products[id] || {}).stock || 0;
                    const needed = data.qty || 1;
                    const possible = needed > 0 ? Math.floor(childStock / needed) : Infinity;
                    if (possible < max) max = possible;
                }
            }
            return max === Infinity ? 0 : max;
        }
    }
}
</script>
@endsection
