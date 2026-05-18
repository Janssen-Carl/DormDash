@extends('layouts.main')

@section('title', 'Orders Overview')

@section('content')
            <div class="mx-auto max-w-6xl px-8 py-12">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Orders</h1>
                    <p class="mt-2 text-gray-600">Manage and track all your orders</p>
                </div>

                {{-- Overview Stats --}}
                <div class="mb-12 grid grid-cols-2 gap-6 sm:grid-cols-4">
                    <a href="/orders" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Orders</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                                <x-heroicon-o-shopping-bag class="h-6 w-6 text-blue-600" />
                            </div>
                        </div>
                    </a>

                    <a href="/analytics" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Spent</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">₱{{ number_format($totalSpent, 2) }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                                <x-heroicon-o-banknotes class="h-6 w-6 text-green-600" />
                            </div>
                        </div>
                    </a>

                    <a href="/orders?status=to_ship" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">To Ship</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $toShipCount }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100">
                                <x-heroicon-o-clock class="h-6 w-6 text-amber-600" />
                            </div>
                        </div>
                    </a>

                    <a href="/orders?status=shipped" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Shipped</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $shippedCount }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                                <x-heroicon-o-truck class="h-6 w-6 text-blue-600" />
                            </div>
                        </div>
                    </a>

                    <a href="/orders?status=delivered" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Delivered</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $deliveredCount }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100">
                                <x-heroicon-o-home class="h-6 w-6 text-indigo-600" />
                            </div>
                        </div>
                    </a>

                    <a href="/orders?status=completed" class="block rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Completed</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $completedCount }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                                <x-heroicon-o-check-circle class="h-6 w-6 text-green-600" />
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Recent Orders --}}
                <div>
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Recent Orders</h2>
                            <p class="mt-1 text-sm text-gray-600">Your latest 5 orders</p>
                        </div>
                        <a href="/orders" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50">
                            View All
                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($recentOrders as $order)
                            <a href="/orders?expand={{ $order->order_id }}#order-{{ $order->order_id }}" class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 transition-all duration-300 hover:shadow-md hover:border-green-300">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <x-heroicon-o-shopping-bag class="h-6 w-6 text-gray-400" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">₱{{ number_format($order->order_total, 2) }}</p>
                                        @php
                                            $status = strtolower($order->order_status);
                                            if ($status === 'to_ship') {
                                                $status = 'to ship';
                                            }
                                            $color = match($status) {
                                                'completed' => 'green',
                                                'delivered' => 'indigo',
                                                'shipped' => 'blue',
                                                'to ship', 'pending' => 'amber',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-{{ $color }}-600"></span>
                                            {{ ucwords($status) }}
                                        </span>
                                    </div>
                                    <div class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 transition-all duration-200 hover:bg-gray-50">
                                        <x-heroicon-o-arrow-right class="h-5 w-5" />
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-xl border border-gray-100 bg-white p-8 text-center text-gray-500">
                                No recent orders found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endsection
