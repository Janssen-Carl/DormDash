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
                class="rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800"
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
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-50">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-50">
                                        @if($product->firstImage())
                                            <img src="{{ asset($product->firstImage()) }}" alt="{{ $product->name }}" class="h-14 w-14 object-cover rounded-2xl">
                                        @else
                                            <span class="text-2xl">🛒</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-zinc-900">{{ $product->name }}</p>
                                    <p class="text-xs text-zinc-500">SKU: {{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-700">{{ $product->brand }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-700">{{ $product->unit }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($product->stock > 10)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $product->stock }} in stock</span>
                            @elseif($product->stock > 0)
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Low Stock ({{ $product->stock }})</span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Out of Stock</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-zinc-900">₱{{ number_format($product->price, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($product->stock > 0)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Available</span>
                            @else
                                <span class="rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-zinc-700">Unavailable</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500">{{ $product->created_at->format('Y-m-d') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('vendor.products.edit', $product->id) }}" class="inline-flex items-center rounded-lg border border-zinc-200 px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100">
                                    Edit
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-red-600 transition hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-zinc-500">No products found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
