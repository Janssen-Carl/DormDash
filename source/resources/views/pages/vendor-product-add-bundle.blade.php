@extends('layouts.vendor-main')

@section('title', 'Add Product Bundle')

@section('content')
<div 
    class="mx-auto max-w-6xl px-6 py-10"
    x-data="{ showPhotoModal: false }"
>

    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900">
            Add Product Bundle
        </h1>

        <p class="mt-2 text-sm text-zinc-500">
            Fill in the bundle details below.
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

                {{-- Bundle Name --}}
                <div>
                    <label 
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Bundle Name
                    </label>

                    <input
                        type="text"
                        name="bundle_name"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label 
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                    ></textarea>
                </div>

            </div>
        </div>

        {{-- ============================= --}}
        {{-- SELECT PRODUCTS FOR BUNDLE --}}
        {{-- ============================= --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <h2 class="mb-8 text-lg font-bold text-gray-900">
                Select Products for Bundle
            </h2>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Select</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Product Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Price</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200">
                        {{-- Placeholder items --}}
                        @php
                        $products = [
                            ['name' => 'Apples', 'qty' => '1pc', 'price' => 80.00],
                            ['name' => 'Whole Wheat Bread', 'qty' => '70g', 'price' => 80.00],
                            ['name' => 'Orange Juice', 'qty' => '1L', 'price' => 120.00],
                        ];
                        @endphp

                        @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_products[]" value="{{ $product['name'] }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900">{{ $product['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">{{ $product['qty'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-zinc-900">₱{{ number_format($product['price'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                    Save Bundle
                </span>
            </button>
        </div>

    </form>
</div>
@endsection