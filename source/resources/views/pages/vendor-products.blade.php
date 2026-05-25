@extends('layouts.vendor-main')

@section('title', 'Products - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10">

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900">
            Product Inventory
        </h1>

        <p class="mt-2 text-sm text-zinc-500">
            Manage your products, inventory, and availability.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Low Stock Alert Banner --}}
    @php
        $lowStockItems = $products->where('stock', '<=', 10)->where('is_active', 1);
    @endphp
    @if($lowStockItems->count() > 0)
        <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4" x-data="{ open: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-sm font-semibold text-amber-800">
                        {{ $lowStockItems->count() }} product{{ $lowStockItems->count() > 1 ? 's' : '' }} running low on stock
                    </p>
                </div>
                <svg class="h-5 w-5 text-amber-600 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div x-show="open" x-collapse class="mt-4 space-y-3">
                @foreach($lowStockItems->sortBy('stock') as $lowItem)
                    <div class="flex items-center justify-between rounded-xl bg-white border border-amber-100 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 shrink-0">
                                @if ($lowItem->images->first())
                                    <img src="{{ asset($lowItem->images->first()->image) }}" alt="{{ $lowItem->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-zinc-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ $lowItem->name }}</p>
                                <p class="text-xs text-{{ $lowItem->stock == 0 ? 'red' : 'amber' }}-600 font-medium">
                                    {{ $lowItem->stock == 0 ? 'Out of stock' : $lowItem->stock . ' remaining' }}
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('vendor.products.restock', $lowItem->item_id) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="status" value="{{ $status ?? 'active' }}">
                            <input type="hidden" name="sort" value="{{ $sortBy ?? 'created_at' }}">
                            <input type="hidden" name="dir" value="{{ $sortDir ?? 'desc' }}">
                            <div class="flex items-center gap-1">
                                <button type="submit" name="quantity" value="10" class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition">+10</button>
                                <button type="submit" name="quantity" value="25" class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition">+25</button>
                                <button type="submit" name="quantity" value="50" class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition">+50</button>
                                <button type="submit" name="quantity" value="100" class="rounded-lg bg-emerald-100 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition">+100</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="mb-8 flex justify-center gap-4">
        <a 
            href="{{ route('vendor.products.bundle') }}"
            class="rounded-xl bg-white border border-emerald-600 px-6 py-3 text-sm font-semibold text-zinc-700"
        >
            Add Bundle
        </a>

        <a 
            href="{{ route('vendor.products.add') }}"
            class="rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
        >
            Add Product
        </a>
    </div>

    {{-- Search and Filter --}}
    <div class="mb-6 space-y-4">
        <form method="GET" action="{{ route('vendor.products') }}" class="flex flex-col sm:flex-row items-center w-full bg-white rounded-2xl shadow-sm border border-zinc-200 p-1.5 focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all duration-200">
            <input type="hidden" name="sort" value="{{ $sortBy ?? 'created_at' }}">
            <input type="hidden" name="dir" value="{{ $sortDir ?? 'desc' }}">
            <input type="hidden" name="status" value="{{ $status ?? 'active' }}">

            <div class="flex-1 w-full relative flex items-center group">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-zinc-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, brands, or SKU..." onchange="this.form.submit()" class="block w-full border-0 py-3 pl-11 pr-4 text-zinc-900 placeholder:text-zinc-400 focus:ring-0 sm:text-sm bg-transparent">
            </div>

            <div class="hidden sm:block w-px h-8 bg-zinc-200 mx-2"></div>

            <div class="w-full sm:w-auto flex items-center gap-2 pt-3 pb-1 px-1 sm:p-0 border-t sm:border-t-0 border-zinc-100 mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 transition-all">
                    Search
                </button>
                @if(request()->hasAny(['search', 'status', 'sort', 'dir']) && (request('search') != '' || request('status') != 'active' || request('sort') != '' || request('dir') != ''))
                    <a href="{{ route('vendor.products') }}" class="flex items-center justify-center rounded-xl bg-red-50 text-red-600 px-3 py-2.5 transition hover:bg-red-100" title="Clear Filters">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>

        {{-- Status Filter Pills (instant refresh) --}}
        <div class="flex gap-2 overflow-x-auto">
            <a href="{{ route('vendor.products') }}?status=active{{ request('search') ? '&search=' . request('search') : '' }}{{ ($sortBy ?? false) ? '&sort=' . $sortBy : '' }}{{ ($sortDir ?? false) ? '&dir=' . $sortDir : '' }}"
               class="{{ ($status ?? 'active') === 'active' ? 'bg-emerald-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
                Active Only
            </a>
            <a href="{{ route('vendor.products') }}?status=inactive{{ request('search') ? '&search=' . request('search') : '' }}{{ ($sortBy ?? false) ? '&sort=' . $sortBy : '' }}{{ ($sortDir ?? false) ? '&dir=' . $sortDir : '' }}"
               class="{{ ($status ?? '') === 'inactive' ? 'bg-emerald-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
                Inactive Only
            </a>
            <a href="{{ route('vendor.products') }}?status=all{{ request('search') ? '&search=' . request('search') : '' }}{{ ($sortBy ?? false) ? '&sort=' . $sortBy : '' }}{{ ($sortDir ?? false) ? '&dir=' . $sortDir : '' }}"
               class="{{ ($status ?? '') === 'all' ? 'bg-emerald-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
                All Products
            </a>
        </div>
    </div>

    {{-- Sort Helper --}}
    @php
        $currentSort = $sortBy ?? 'created_at';
        $currentDir = $sortDir ?? 'desc';

        function sortUrl($column, $currentSort, $currentDir) {
            $newDir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery(['sort' => $column, 'dir' => $newDir]);
        }

        function sortIcon($column, $currentSort, $currentDir) {
            if ($currentSort !== $column) {
                return '<svg class="inline h-3.5 w-3.5 ml-1 text-zinc-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>';
            }
            if ($currentDir === 'asc') {
                return '<svg class="inline h-3.5 w-3.5 ml-1 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>';
            }
            return '<svg class="inline h-3.5 w-3.5 ml-1 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>';
        }
    @endphp

    {{-- Product Table --}}
    <div class="w-full max-w-full overflow-x-auto rounded-3xl border border-zinc-200 bg-white shadow-sm">

        <table class="min-w-full divide-y divide-zinc-200">

            {{-- Table Header --}}
            <thead class="bg-zinc-50">
                <tr>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        Product
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        Brand
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        Unit
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        <a href="{{ sortUrl('stock', $currentSort, $currentDir) }}" class="inline-flex items-center hover:text-emerald-600 transition-colors">
                            Stock {!! sortIcon('stock', $currentSort, $currentDir) !!}
                        </a>
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        <a href="{{ sortUrl('price', $currentSort, $currentDir) }}" class="inline-flex items-center hover:text-emerald-600 transition-colors">
                            Price {!! sortIcon('price', $currentSort, $currentDir) !!}
                        </a>
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        <a href="{{ sortUrl('is_available', $currentSort, $currentDir) }}" class="inline-flex items-center hover:text-emerald-600 transition-colors">
                            Status {!! sortIcon('is_available', $currentSort, $currentDir) !!}
                        </a>
                    </th>

                    <th class="px-4.5 py-4 text-left text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        <a href="{{ sortUrl('created_at', $currentSort, $currentDir) }}" class="inline-flex items-center hover:text-emerald-600 transition-colors">
                            Added {!! sortIcon('created_at', $currentSort, $currentDir) !!}
                        </a>
                    </th>

                    <th class="px-4.5 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-500 whitespace-nowrap">
                        Actions
                    </th>

                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="divide-y divide-zinc-100 bg-white">

                @forelse ($products as $product)
                <tr class="transition hover:bg-zinc-50 {{ $product->stock <= 5 ? 'bg-red-50/40' : '' }}">

                    {{-- Product --}}
                    <td class="whitespace-nowrap px-4.5 py-4">
                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shrink-0">
                                @if ($product->images->first())
                                    <img src="{{ asset($product->images->first()->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-zinc-300">
                                        <x-heroicon-o-photo class="h-6 w-6" />
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-zinc-900">
                                        {{ $product->name }}
                                    </p>
                                    @if($product->is_bundle)
                                        <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wider">Bundle</span>
                                    @endif
                                </div>

                                <p class="text-xs text-zinc-500">
                                    SKU: {{ $product->sku ?? 'N/A' }}
                                </p>
                            </div>

                        </div>
                    </td>

                    {{-- Brand --}}
                    <td class="whitespace-nowrap px-4.5 py-4 text-sm text-zinc-700">
                        {{ $product->brand ?? 'N/A' }}
                    </td>

                    {{-- Unit --}}
                    <td class="whitespace-nowrap px-4.5 py-4 text-sm text-zinc-700">
                        @if($product->is_bundle)
                            <span class="text-zinc-400 italic font-medium">Bundle Package</span>
                        @else
                            {{ $product->unit_value ? ($product->unit_value . ' ' . $product->unit_type) : ($product->unit_type ?? 'N/A') }}
                        @endif
                    </td>

                    {{-- Stock --}}
                    <td class="whitespace-nowrap px-4.5 py-4">
                        @if ($product->stock <= 5)
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                {{ $product->stock == 0 ? 'Out of Stock' : 'Low (' . $product->stock . ')' }}
                            </span>
                        @elseif ($product->stock <= 10)
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                {{ $product->stock }} in stock
                            </span>
                        @else
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ $product->stock }} in stock
                            </span>
                        @endif
                    </td>

                    {{-- Price --}}
                    <td class="whitespace-nowrap px-4.5 py-4 text-sm font-bold text-zinc-900">
                        ₱{{ number_format($product->price, 2) }}
                    </td>

                    {{-- Availability --}}
                    <td class="whitespace-nowrap px-4.5 py-4">

                        @if ($product->is_available)
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Available
                            </span>
                        @else
                            <span class="rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-zinc-700">
                                Unavailable
                            </span>
                        @endif

                    </td>

                    {{-- Date Added --}}
                    <td class="whitespace-nowrap px-4.5 py-4 text-sm text-zinc-500">
                        {{ $product->created_at ? $product->created_at->format('Y-m-d') : 'N/A' }}
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-4.5 py-4 text-right">

                        <div class="flex justify-end gap-2">

                            {{-- Discount / Promotion --}}
                            @if($product->discounts->isNotEmpty())
                                <a 
                                    href="{{ route('vendor.discounts.edit', $product->discounts->first()->discount_id) }}"
                                    class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100"
                                    title="Edit Active Promotion ({{ $product->discounts->first()->name }})"
                                >
                                    <x-heroicon-o-sparkles class="h-4 w-4 text-emerald-600" />
                                </a>
                            @else
                                <a 
                                    href="{{ route('vendor.discounts.create') }}?item_id={{ $product->item_id }}"
                                    class="inline-flex items-center rounded-lg border border-zinc-200 px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100"
                                    title="Add Discount / Launch Promotion"
                                >
                                    <x-heroicon-o-sparkles class="h-4 w-4" />
                                </a>
                            @endif

                            {{-- Edit --}}
                            <a 
                                href="{{ route('vendor.products.edit', $product->item_id) }}"
                                class="inline-flex items-center rounded-lg border border-zinc-200 px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100"
                            >
                                <x-heroicon-o-pencil-square class="h-4 w-4" />
                            </a>

                            {{-- Delete --}}
                            <form 
                                action="{{ route('vendor.products.destroy', $product->item_id) }}" 
                                method="POST" 
                                onsubmit="return confirm('Are you sure you want to remove this product? It will be deactivated and hidden from customers.')"
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-red-600 transition hover:bg-red-50"
                                >
                                    <x-heroicon-o-trash class="h-4 w-4" />
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-sm text-zinc-500">
                        <div class="flex flex-col items-center justify-center">
                            <x-heroicon-o-inbox class="h-10 w-10 text-zinc-400 mb-2" />
                            <p class="font-medium text-zinc-600">No products found</p>
                            <p class="text-xs text-zinc-400 mt-1">Start by adding your first product using the button above.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection