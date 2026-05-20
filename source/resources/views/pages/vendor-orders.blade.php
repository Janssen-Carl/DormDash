@extends('layouts.vendor-main')

@section('title', 'Active Orders - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-12">
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Active Orders</h1>
            <p class="mt-2 text-gray-600">Manage and fulfill your customer orders.</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-semibold text-gray-600">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ $orders->count() ?? 0 }}</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-8 rounded-lg bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <p class="font-semibold text-emerald-900">Success</p>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Filter Buttons --}}
    <div class="mb-8 flex gap-2 border-b border-gray-200 pb-4 overflow-x-auto">
        <a href="{{ route('vendor.orders') }}" class="{{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            All
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'pending']) }}" class="{{ request('status') === 'pending' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            Not Confirmed
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'to_ship']) }}" class="{{ request('status') === 'to_ship' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            To Ship
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'shipped']) }}" class="{{ request('status') === 'shipped' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            Shipped
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'delivered']) }}" class="{{ request('status') === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            Delivered
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'completed']) }}" class="{{ request('status') === 'completed' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors whitespace-nowrap">
            Completed
        </a>
    </div>

    {{-- Orders List --}}
    @if($orders->isNotEmpty())
        <div class="space-y-6">
            @foreach($orders as $order)
                @php
                    $status = strtolower($order->order_status);
                    if ($status === 'to_ship') $status = 'to ship';
                    if ($status === 'pending') $status = 'not confirmed';
                    $color = match($order->order_status) {
                        'completed' => 'emerald',
                        'delivered' => 'emerald',
                        'shipped' => 'blue',
                        'to_ship' => 'amber',
                        'pending' => 'gray',
                        default => 'gray'
                    };
                @endphp
                <div x-data="{ expanded: false }" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-gray-200 transition-all">
                    {{-- Order Header --}}
                    <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between cursor-pointer" @click="expanded = !expanded">
                        <div class="flex-1">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                                        @if($order->customer)
                                            • {{ $order->customer->first_name ?? '' }} {{ $order->customer->last_name ?? '' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                                    <span class="h-2 w-2 rounded-full bg-{{ $color }}-600"></span>
                                    <span>{{ ucwords($status) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-right">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total</p>
                                <p class="text-xl font-bold text-gray-900">₱{{ number_format($order->order_total, 2) }}</p>
                            </div>
                            <div class="text-gray-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Expandable Content --}}
                    <div x-show="expanded" x-collapse class="border-t border-gray-100 px-6 py-6 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Order Items --}}
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Order Items</h4>
                                <div class="space-y-4">
                                    @foreach ($order->items as $item)
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="h-12 w-12 rounded-lg border border-gray-200 bg-white overflow-hidden flex-shrink-0">
                                                    @if ($item->images->first())
                                                        <img src="{{ asset($item->images->first()->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                                                    @else
                                                        <svg class="h-full w-full p-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $item->pivot->quantity }} x ₱{{ number_format($item->pivot->price, 2) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="font-semibold text-gray-900">
                                                ₱{{ number_format($item->pivot->quantity * $item->pivot->price, 2) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Delivery & Actions --}}
                            <div class="space-y-6">
                                {{-- Delivery Info --}}
                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                    <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        Delivery Information
                                    </h4>
                                    <div class="space-y-3 text-sm">
                                        @if($order->address)
                                            <div>
                                                <p class="text-gray-500">{{ $order->address->street }}</p>
                                                <p class="text-gray-500">{{ $order->address->city }}, {{ $order->address->province_state }} {{ $order->address->postal_code }}</p>
                                            </div>
                                        @else
                                            <p class="text-gray-400 italic">No address provided</p>
                                        @endif
                                        <div class="border-t border-gray-100 pt-3">
                                            <p class="text-gray-600">
                                                <span class="font-semibold">Shipping:</span>
                                                <span class="capitalize">{{ $order->shipping_method ?? 'Standard' }}</span>
                                            </p>
                                            @if($order->tracking_number)
                                                <p class="text-gray-600">
                                                    <span class="font-semibold">Tracking:</span>
                                                    {{ $order->tracking_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="space-y-2">
                                    @if($order->order_status === 'pending')
                                        <form action="{{ route('vendor.orders.confirm', $order->order_id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                                                Confirm Order
                                            </button>
                                        </form>
                                    @elseif($order->order_status === 'to_ship')
                                        <form action="{{ route('vendor.orders.ship', $order->order_id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                                Mark as Shipped
                                            </button>
                                        </form>
                                    @elseif($order->order_status === 'shipped')
                                        <form action="{{ route('vendor.orders.deliver', $order->order_id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                                                Mark as Delivered
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl bg-white border border-gray-200 p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No orders found</h3>
            <p class="text-gray-600">
                @if(request('status') && request('status') !== 'all')
                    No orders with status "{{ request('status') }}"
                @else
                    When customers place orders for your products, they will appear here.
                @endif
            </p>
        </div>
    @endif
</div>

@vite(['resources/js/alpine.js'])
@endsection
