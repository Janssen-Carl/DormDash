@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
            <div class="mx-auto max-w-6xl px-8 py-12">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Your Orders</h1>
                    <p class="mt-2 text-gray-600">Track and manage your orders</p>
                </div>

                <div class="space-y-6">
                    @forelse ($orders as $order)
                        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-md hover:border-gray-200">
                            <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</h3>
                                            <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                                        </div>

                                        @php
                                            $status = strtolower($order->order_status);
                                            $color = match($status) {
                                                'delivered', 'completed' => 'green',
                                                'in transit', 'shipping' => 'blue',
                                                'pending', 'processing' => 'amber',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                                            <span class="h-2 w-2 rounded-full bg-{{ $color }}-600"></span>
                                            <span>{{ ucfirst($status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 px-6 py-6">
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
                                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Order Total</p>
                                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($order->order_total, 2) }}</p>
                                    </div>

                                    <div class="flex gap-3">
                                        <a href="{{ route('orders.track', $order->tracking_number ?? 'untracked') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-100 hover:border-gray-300">
                                            <x-heroicon-o-truck class="h-4 w-4" />
                                            Track Order
                                        </a>

                                        <form action="{{ route('checkout.index') }}" method="GET" class="m-0">
                                            <input type="hidden" name="reorder_id" value="{{ $order->order_id }}">
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300">
                                                <x-heroicon-o-arrow-path class="h-4 w-4" />
                                                Reorder
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty

                        <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center">
                            <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-gray-600">Start shopping to create your first order!</p>
                            <a href="/products" class="mt-6 inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-green-700">
                                Browse Products
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        @endsection
