@extends('layouts.vendor-main')

@section('title', 'Active Orders - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Active Orders</h1>
            <p class="mt-2 text-gray-600">Manage, confirm, and fulfill your customer orders in real-time.</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 self-start md:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Live Fulfillment Dashboard
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-start gap-3 shadow-sm transition-all duration-300">
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <p class="font-semibold">Success</p>
                <p class="text-sm text-emerald-700/90 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start gap-3 shadow-sm transition-all duration-300">
            <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <p class="font-semibold">Error</p>
                <p class="text-sm text-rose-700/90 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Cards Overview (Statistics) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Card 1: Total Orders -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400">
                Lifetime orders associated with your products
            </div>
        </div>

        <!-- Card 2: Pending Confirmation (Not Confirmed Yet) -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md hover:border-amber-300 transition-colors group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1 group-hover:text-amber-600 transition-colors">Not Confirmed Yet</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-amber-600 font-medium">
                Awaiting your confirmation to fulfill
            </div>
        </div>

        <!-- Card 3: Confirmed Orders -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md hover:border-emerald-300 transition-colors group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1 group-hover:text-emerald-600 transition-colors">Confirmed Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $confirmedCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-emerald-600 font-medium">
                Active orders currently being fulfilled
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <form action="{{ route('vendor.orders') }}" method="GET" class="bg-white rounded-2xl p-4 border border-gray-200 shadow-md mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-grow relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Search by Order ID, customer, tracking number, product name..." 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm text-gray-900">
            </div>
            
            <!-- Status Selector -->
            <div class="w-full md:w-64 relative">
                <select name="status" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm text-gray-700 appearance-none">
                    <option value="all" {{ ($status ?? '') === 'all' || !($status ?? '') ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Not Confirmed Yet (Pending)</option>
                    <option value="confirmed" {{ ($status ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed (All)</option>
                    <option value="to_ship" {{ ($status ?? '') === 'to_ship' ? 'selected' : '' }}>To Ship</option>
                    <option value="shipped" {{ ($status ?? '') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ ($status ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-grow md:flex-grow-0 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm hover:shadow-md">
                    Search
                </button>
                @if($search || ($status && $status !== 'all'))
                    <a href="{{ route('vendor.orders') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors text-center flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Active Orders List -->
    @if(isset($orders) && $orders->isNotEmpty())
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-gray-200 shadow-md overflow-hidden hover:shadow-lg transition-all duration-200">
                    <!-- Order Card Header -->
                    <div class="bg-gray-50 px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-lg font-bold text-gray-900">Order #{{ $order->order_id }}</span>
                            <span class="text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider
                                @if($order->order_status === 'pending')
                                    bg-gray-100 text-gray-700 border border-gray-200
                                @elseif($order->order_status === 'to_ship')
                                    bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($order->order_status === 'shipped')
                                    bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($order->order_status === 'delivered')
                                    bg-emerald-50 text-emerald-700 border border-emerald-200
                                @else
                                    bg-green-50 text-green-700 border border-green-200
                                @endif">
                                @if($order->order_status === 'pending')
                                    Not Confirmed Yet
                                @elseif($order->order_status === 'to_ship')
                                    Confirmed (To Ship)
                                @else
                                    {{ $order->order_status }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Order Card Body -->
                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Column 1 & 2: Customer & Products -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Customer & Delivery Info -->
                            <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-100">
                                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Delivery Information
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
                                    <div>
                                        <p class="text-xs text-gray-400">Customer</p>
                                        <p class="font-medium text-gray-900 mt-0.5">
                                            {{ ($order->customer->first_name ?? '') . ' ' . ($order->customer->last_name ?? '') }} 
                                            <span class="text-xs text-gray-400">({{ $order->customer->user->username ?? 'User' }})</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Shipping Method</p>
                                        <p class="font-medium text-gray-900 mt-0.5 capitalize">{{ $order->shipping_method ?? 'Standard' }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-xs text-gray-400">Address</p>
                                        <p class="font-medium text-gray-900 mt-0.5">
                                            @if($order->address)
                                                {{ $order->address->street }}, {{ $order->address->city }}, {{ $order->address->province_state }} {{ $order->address->postal_code }}, {{ $order->address->country }}
                                            @else
                                                <span class="text-gray-400 italic">No address provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Products from this Vendor -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 mb-3">Your Products in this Order</h3>
                                <div class="divide-y divide-gray-100">
                                    @php $vendorSubtotal = 0; @endphp
                                    @foreach($order->items as $item)
                                        @php 
                                            $itemSubtotal = $item->pivot->quantity * $item->pivot->price;
                                            $vendorSubtotal += $itemSubtotal;
                                        @endphp
                                        <div class="py-3 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200 flex items-center justify-center">
                                                    @if($item->images->isNotEmpty())
                                                        <img src="{{ $item->images->first()->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-xs text-gray-400 font-bold uppercase">Item</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900">{{ $item->name }}</h4>
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        ₱{{ number_format($item->pivot->price, 2) }} × {{ $item->pivot->quantity }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-bold text-gray-900">₱{{ number_format($itemSubtotal, 2) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Summary & Actions -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex flex-col justify-between gap-6">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 mb-4">Fulfillment Summary</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Your Subtotal:</span>
                                        <span class="font-medium text-gray-900">₱{{ number_format($vendorSubtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Delivery Fee:</span>
                                        <span class="font-medium text-gray-900">₱50.00</span>
                                    </div>
                                    <div class="border-t border-gray-200 my-2 pt-2 flex justify-between font-bold text-gray-900">
                                        <span>Order Total:</span>
                                        <span>₱{{ number_format($order->order_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- State-Based Actions -->
                            <div>
                                @if($order->order_status === 'pending')
                                    <!-- Awaiting Confirmation -->
                                    <form action="{{ route('vendor.orders.confirm', $order->order_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Confirm Order
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'to_ship')
                                    <!-- Confirmed, Ready to Ship -->
                                    <form action="{{ route('vendor.orders.ship', $order->order_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                            Mark as Shipped
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'shipped')
                                    <!-- Shipped, In Transit -->
                                    <div class="mb-3 text-xs text-gray-400 text-center">
                                        Tracking: <span class="font-mono text-gray-700">{{ $order->tracking_number }}</span>
                                    </div>
                                    <form action="{{ route('vendor.orders.deliver', $order->order_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Mark as Delivered
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'delivered' || $order->order_status === 'completed')
                                    <!-- Delivered / Fully Completed -->
                                    <div class="flex items-center justify-center gap-2 text-emerald-600 bg-emerald-50 py-2.5 rounded-xl border border-emerald-100 font-semibold text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Fulfillment Complete!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Orders Fallback -->
        <div class="bg-white rounded-3xl p-12 border border-gray-200 shadow-md text-center">
            @if($search || ($status && $status !== 'all'))
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-50 mb-6 text-amber-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No matching orders found</h3>
                <p class="text-gray-500 max-w-md mx-auto leading-relaxed mb-6">We couldn't find any orders matching your search query or status filter. Try clearing or updating your filters.</p>
                <a href="{{ route('vendor.orders') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors shadow-sm">
                    Clear Search & Filters
                </a>
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6 text-emerald-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No active orders</h3>
                <p class="text-gray-500 max-w-md mx-auto leading-relaxed">When customers place orders for your products, they will appear here for you to manage, confirm, and fulfill.</p>
            @endif
        </div>
    @endif
</div>
@endsection
