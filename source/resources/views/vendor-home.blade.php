@extends('layouts.vendor-main')

@section('title', 'Vendor Dashboard - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 p-8 sm:p-12 text-white shadow-2xl mb-10">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-40 -mt-40"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full -ml-36 -mb-36"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="mb-2 text-sm font-semibold tracking-widest text-emerald-100 uppercase">
                    Welcome Back
                </p>
                <h1 class="mb-4 text-4xl sm:text-5xl leading-tight font-bold">
                    {{ $vendor ? $vendor->name : auth()->user()->username }}!
                </h1>
                <p class="text-lg leading-relaxed text-emerald-50/80 max-w-2xl">
                    Here's what's happening with your store today. Keep up the great work!
                </p>
            </div>
            <div class="flex-shrink-0 flex flex-wrap gap-3">
                <a href="{{ route('vendor.products.add') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium rounded-xl text-emerald-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Stat Card 1 -->
        <a href="{{ route('vendor.products') }}" class="block bg-white rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 hover:border-emerald-400 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-500 mb-1 group-hover:text-emerald-600 transition-colors">Total Products</p>
                    <p class="text-3xl font-bold text-zinc-900">{{ $totalProducts ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-emerald-500 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    12%
                </span>
                <span class="text-zinc-400 ml-2">from last month</span>
            </div>
        </a>

        <!-- Stat Card 2 -->
        <a href="{{ route('vendor.orders') }}" class="block bg-white rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 hover:border-emerald-400 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-500 mb-1 group-hover:text-emerald-600 transition-colors">Active Orders</p>
                    <p class="text-3xl font-bold text-zinc-900">0</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-fuchsia-50 flex items-center justify-center text-fuchsia-600 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-emerald-500 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    4%
                </span>
                <span class="text-zinc-400 ml-2">from last week</span>
            </div>
        </a>

        <!-- Stat Card 3 -->
        <a href="{{ route('vendor.analytics') }}" class="block bg-white rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 hover:border-emerald-400 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-500 mb-1 group-hover:text-emerald-600 transition-colors">Total Revenue</p>
                    <p class="text-3xl font-bold text-zinc-900">₱0.00</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-zinc-400">Just getting started!</span>
            </div>
        </a>
    </div>

    <!-- Recent Activity Placeholder -->
    <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-md">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-zinc-900">Recent Activity</h2>
            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">View all</a>
        </div>
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-zinc-900 mb-1">No recent activity</h3>
            <p class="text-zinc-500 max-w-sm">When you receive new orders or updates, they will appear here.</p>
        </div>
    </div>
</div>


@endsection