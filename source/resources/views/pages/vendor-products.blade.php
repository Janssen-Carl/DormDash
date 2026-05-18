@extends('layouts.vendor-main')

@section('title', 'Vendor Product Stock')

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

    {{-- Product Table --}}
    <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm">

        <table class="min-w-full divide-y divide-zinc-200">

            {{-- Table Header --}}
            <thead class="bg-zinc-50">
                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Product
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Brand
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Unit
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Stock
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Price
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Added
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-zinc-500">
                        Actions
                    </th>

                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="divide-y divide-zinc-100 bg-white">

                @forelse ($products as $product)
                <tr class="transition hover:bg-zinc-50">

                    {{-- Product --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50">
                                @if ($product->images->first())
                                    <img src="{{ asset($product->images->first()->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-zinc-300">
                                        <x-heroicon-o-photo class="h-6 w-6" />
                                    </div>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-zinc-900">
                                    {{ $product->name }}
                                </p>

                                <p class="text-xs text-zinc-500">
                                    SKU: {{ $product->sku ?? 'N/A' }}
                                </p>
                            </div>

                        </div>
                    </td>

                    {{-- Brand --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-700">
                        {{ $product->brand ?? 'N/A' }}
                    </td>

                    {{-- Unit --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-700">
                        {{ $product->unit_value ? ($product->unit_value . ' ' . $product->unit_type) : ($product->unit_type ?? 'N/A') }}
                    </td>

                    {{-- Stock --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        @if ($product->stock <= 5)
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                Low Stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ $product->stock }} in stock
                            </span>
                        @endif

                    </td>

                    {{-- Price --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-zinc-900">
                        ₱{{ number_format($product->price, 2) }}
                    </td>

                    {{-- Availability --}}
                    <td class="whitespace-nowrap px-6 py-4">

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
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500">
                        {{ $product->created_at ? $product->created_at->format('Y-m-d') : 'N/A' }}
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-6 py-4 text-right">

                        <div class="flex justify-end gap-2">

                            {{-- Edit --}}
                            <a 
                                href="{{ route('vendor.products.edit', $product->item_id) }}"
                                class="inline-flex items-center rounded-lg border border-zinc-200 px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100"
                            >
                                <x-heroicon-o-pencil-square class="h-4 w-4" />
                            </a>

                            {{-- Delete --}}
                            <button
                                class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-red-600 transition hover:bg-red-50"
                            >
                                <x-heroicon-o-trash class="h-4 w-4" />
                            </button>

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