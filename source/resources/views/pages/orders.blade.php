@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
<div class="mx-auto max-w-6xl px-8 py-12">
    <div class="mb-8 flex items-center gap-4">
        <a href="/orders-overview" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-900">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Your Orders</h1>
            <p class="mt-1 text-sm text-gray-600">Track and manage your orders</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-8 flex gap-2 border-b border-gray-200 pb-4 overflow-x-auto">
        <a href="/orders" class="{{ !request('status') ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors">
            All
        </a>
        <a href="/orders?status=to_ship" class="{{ request('status') === 'to_ship' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors">
            To Ship
        </a>
        <a href="/orders?status=shipped" class="{{ request('status') === 'shipped' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors">
            Shipped
        </a>
        <a href="/orders?status=delivered" class="{{ request('status') === 'delivered' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors">
            Delivered
        </a>
        <a href="/orders?status=completed" class="{{ request('status') === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-full px-4 py-2 text-sm font-semibold transition-colors">
            Completed
        </a>
    </div>

    <div class="space-y-6">
        @forelse ($orders as $order)
            @php
                $status = strtolower($order->order_status);
                if ($status === 'to_ship') $status = 'to ship';
                $color = match($status) {
                    'completed' => 'green',
                    'delivered' => 'indigo',
                    'shipped' => 'blue',
                    'to ship', 'pending' => 'amber',
                    default => 'gray'
                };
            @endphp
            <div x-data="{ expanded: false }" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-md hover:border-gray-200">
                <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between cursor-pointer" @click="expanded = !expanded">
                    <div class="flex-1">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
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
                            <x-heroicon-o-chevron-down class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <div x-show="expanded" x-collapse>
                    <div class="border-t border-gray-100 px-6 py-6 bg-gray-50/50">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Order Items</h4>
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-16 overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center">
                                            @if ($item->images->first())
                                                <img src="{{ asset($item->images->first()->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                                            @else
                                                <x-heroicon-o-photo class="h-8 w-8 text-gray-400" />
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-500">Quantity: {{ $item->pivot->quantity }}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-gray-900">₱{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-6">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-end">
                            <div class="flex gap-3">
                                @if($order->order_status !== 'completed' && $order->order_status !== 'delivered')
                                <a href="{{ route('orders.track', $order->tracking_number ?? 'untracked') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-100 hover:border-gray-300">
                                    <x-heroicon-o-truck class="h-4 w-4" />
                                    Track Order
                                </a>
                                @endif

                                @if($order->order_status === 'delivered')
                                <form action="{{ route('orders.complete', $order->order_id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 border border-transparent px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:bg-indigo-700 shadow-sm">
                                        <x-heroicon-o-check-badge class="h-4 w-4" />
                                        Complete Order
                                    </button>
                                </form>
                                @endif

                                @if($order->order_status === 'completed')
                                <form action="{{ route('checkout.index') }}" method="GET" class="m-0">
                                    <input type="hidden" name="reorder_id" value="{{ $order->order_id }}">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300">
                                        <x-heroicon-o-arrow-path class="h-4 w-4" />
                                        Reorder
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty

            <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center">
                <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders found</h3>
                <p class="mt-1 text-gray-600">You don't have any orders matching this filter.</p>
                <a href="/products" class="mt-6 inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                    Browse Products
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
