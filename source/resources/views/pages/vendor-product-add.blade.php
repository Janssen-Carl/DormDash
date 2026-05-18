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
                                        id = "name"
                                        name = "name"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Description</label>
                                    <input
                                        type ="Description"
                                        id = "description"
                                        name = "description"
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
                                        id = "stock"
                                        name = "stock"
                                        placeholder = "Enter Stocks"
                                        class = "w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        >
                                </div>

                                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    {{-- Image URL --}}
                    <div class="lg:col-span-1">
                        <h1 class="block text-sm font-semibold text-gray-700 mb-2">URL</h1>
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="flex flex-col items-center text-center">
                                {{-- Profile Picture with Edit Button --}}
                                <div class="relative group">
                                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 text-4xl font-bold text-white shadow-2xl">
                                        LCC
                                    </div>
                                    <button 
                                        @click="showPhotoModal = true"
                                        type="button" 
                                        class="absolute bottom-0 right-0 flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white shadow-lg transition-all duration-200 hover:bg-green-700 hover:shadow-xl opacity-0 group-hover:opacity-100"
                                    >
                                        <x-heroicon-o-camera class="h-4 w-4" />
                                    </button>
                                </div>

                                <p class="mt-1 text-xs text-gray-500">Change this item icon to your desire</p>
                            </div>
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
                                </div>
                            </div>

                                <button type="button" class="px-10 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50">Cancel</button>
                                <button type="button" class="px-10 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Product</button>
                           
                            
                            </div>
                        
            </div>
@endsection