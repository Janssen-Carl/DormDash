@extends('layouts.vendor-main')

@section('title', 'Active Orders - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Active Orders</h1>
        <p class="mt-2 text-gray-600">Manage and fulfill your customer orders.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-md text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 mb-4 text-emerald-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No active orders</h3>
        <p class="text-gray-500 max-w-md mx-auto">When customers place orders for your products, they will appear here for you to fulfill.</p>
    </div>
</div>
@endsection
