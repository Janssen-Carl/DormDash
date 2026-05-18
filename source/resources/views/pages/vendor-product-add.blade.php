@extends('layouts.vendor-main')

@section('title', 'Add Product')

@section('content')
<div class="mx-auto max-w-7xl px-8 py-12">
    <div class="text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900">Add Product</h1>
        <p class="mt-2 text-zinc-500 text-sm">Fill in the details for the new product you want to add.</p>
        <button type="button" class="px-10 py-2 bg-gray-100 text-black rounded-lg hover:bg-gray-200">General Info</button>
        <button type="button" class="px-10 py-2 bg-gray-100 text-black rounded-lg hover:bg-gray-200">Pricing</button>
        <button type="button" class="px-10 py-2 bg-gray-100 text-black rounded-lg hover:bg-gray-200">Images</button>
    </div>

               {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Add Product --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <h3 class="mb-8 text-lg font-bold text-gray-900">Product Information</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
                                   <input
                                        type ="text" 
                                        id = "product-name"
                                        name = "product-name"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Description</label>
                                    <input
                                        type ="Description"
                                        id = "product-description"
                                        name = "product-description"
                                        rows = "4"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                 </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price</label>
                                    <input
                                        type ="number"
                                        id = "price"
                                        name = "price"
                                        placeholder = "Enter Price"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stocks</label>
                                    <input
                                        type ="number"
                                        id = "stocks"
                                        name = "stocks"
                                        placeholder = "Enter Stocks"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                </div>

                                <div>
                                    <label for="product-image-url" class="block text-sm font-semibold text-gray-700 mb-2">URL</label>
                                    <input
                                        type ="text"
                                        id = "product-image-url"
                                        name = "product-image-url"
                                        placeholder = "Enter Product Image URL"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                </div>
                                </div>
                                

                                <div>
                                    <label for="bundle" class="block text-sm font-semibold text-gray-700 mb-2">Bundle</label>
                                    <select
                                        id = "bundle"
                                        name = "bundle"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                        <option value="">Select Bundle</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                </div>
                                </div>

                                <button type="button" class="px-10 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">Cancel</button>
                                <button type="button" class="px-10 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Product</button>
                            </div>
                        </div>
@endsection