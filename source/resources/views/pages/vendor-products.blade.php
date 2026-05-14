@extends('layouts.vendor-main')

@section('title', 'Vendor Product Stock')

@section('content')
<div class="mx-auto max-w-7xl px-8 py-12">
    {{-- Header Section --}}
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900">Product Stock</h1>
        <p class="mt-2 text-zinc-500 text-sm">Make changes to your product inventory.</p>
    </div>

    {{-- Product Table Section --}}
    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200">
            <thead class="bg-zinc-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Image</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Product Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Date Added</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Price</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Delete</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-zinc-200">
            
                {{-- Placeholder iitems only --}}
                @php
                $products = [
                    ['name' => 'Apples', 'qty' => '1pc', 'date' => '2026-01-01', 'price' => 80.00, 'emoji' => '🍎'],
                    ['name' => 'Bundle 1', 'qty' => '1pc', 'date' => '2026-01-01', 'price' => 200.00, 'emoji' => '🧺'],
                    ['name' => 'Whole Wheat Bread', 'qty' => '70g', 'date' => '2026-01-01', 'price' => 80.00, 'emoji' => '🍞'],
                    ['name' => 'Bundle 2', 'qty' => '1pc', 'date' => '2026-01-01', 'price' => 170.00, 'emoji' => '🧺'],
                ];
                @endphp

                @foreach ($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-12 w-12 flex-shrink-0">
                                <div class="flex h-full w-full items-center justify-center rounded-lg bg-zinc-50 text-2xl border border-zinc-100">
                                    {{ $product['emoji'] }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900">{{ $product['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">{{ $product['qty'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">{{ $product['date'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-zinc-900">₱{{ number_format($product['price'], 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-emerald-600 hover:text-emerald-900">Edit</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-zinc-400 hover:text-red-500">
                            <x-heroicon-o-trash class="h-5 w-5" />
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Action Buttons --}}
    
    <div class="mt-8 flex justify-end gap-3">
        <button type="button" class="px-10 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">Cancel</button>
        <button type="button" class="px-10 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save Changes</button>
    </div> 
     
    
</div>
@endsection