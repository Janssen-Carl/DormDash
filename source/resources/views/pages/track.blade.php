@extends('layouts.main')

@section('title', 'Track Order')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    {{-- Top navigation / Breadcrumbs --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="/orders" class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Back to My Orders
            </a>
        </div>
        <div class="flex items-center gap-3.5">
            <span class="rounded-full bg-gray-100 px-3.5 py-1 text-xs font-semibold text-gray-600">
                Ordered on {{ $order->created_at->format('M d, Y h:i A') }}
            </span>
            @if($order->order_status === 'pending')
                <form action="{{ route('orders.cancel', $order->order_id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-red-50 border border-red-200 px-4 py-2 text-xs font-bold text-red-600 transition-all duration-200 hover:bg-red-100 hover:border-red-300 shadow-sm">
                        <x-heroicon-o-x-circle class="h-4 w-4 shrink-0 text-red-500" />
                        Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Main Dual Column Layout --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        
        {{-- Left Column: Timeline, Stepper & Order Details (7 Cols) --}}
        <div class="lg:col-span-7 space-y-6">
            
            {{-- Stepper Progress Card --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5 mb-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Tracking Number</p>
                        <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2 mt-1">
                            {{ $order->tracking_number ?? 'Pending Confirmation' }}
                            @if($order->tracking_number)
                                <button onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}')" class="text-gray-400 hover:text-gray-600 transition-colors" title="Copy tracking code">
                                    <x-heroicon-o-document-duplicate class="h-4 w-4" />
                                </button>
                            @endif
                        </h1>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'to_ship' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'delivered' => 'bg-green-50 text-green-700 border-green-200',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending Confirmation',
                                'to_ship' => 'Preparing Order',
                                'shipped' => 'Out for Delivery',
                                'delivered' => 'Delivered',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ];
                            $status = $order->order_status;
                        @endphp
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1 text-sm font-semibold {{ $statusClasses[$status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                            <span class="h-2 w-2 rounded-full bg-current"></span>
                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                        </span>
                    </div>
                </div>

                @if($status === 'cancelled')
                    <div class="rounded-xl bg-red-50 border border-red-100 p-4 text-sm text-red-800 flex gap-3">
                        <x-heroicon-s-x-circle class="h-5 w-5 shrink-0 text-red-500" />
                        <div>
                            <p class="font-semibold">Order Cancelled</p>
                            <p class="mt-1 text-red-700">This order was cancelled and is no longer being tracked. If you believe this is an error, please contact customer support.</p>
                        </div>
                    </div>
                @else
                    {{-- Interactive Stepper --}}
                    @php
                        $stages = [
                            ['key' => 'placed', 'label' => 'Order Placed', 'icon' => 'o-document-text', 'desc' => 'Order received by vendor'],
                            ['key' => 'preparing', 'label' => 'Preparing', 'icon' => 'o-cog-6-tooth', 'desc' => 'Vendor packing items'],
                            ['key' => 'shipping', 'label' => 'In Transit', 'icon' => 'o-truck', 'desc' => 'Rider en route to dorm'],
                            ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'o-check-badge', 'desc' => 'Arrived at destination']
                        ];
                        
                        // Map database statuses to stepper step indices (0-3)
                        $activeIndex = 0; // placed
                        if ($status === 'to_ship') $activeIndex = 1;
                        if ($status === 'shipped') $activeIndex = 2;
                        if (in_array($status, ['delivered', 'completed'])) $activeIndex = 3;
                    @endphp

                    <div class="relative py-4">
                        {{-- Connecting progress bar --}}
                        <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-gray-100 sm:left-0 sm:right-0 sm:top-10 sm:bottom-auto sm:h-0.5 sm:w-full sm:flex" aria-hidden="true">
                            <div class="bg-green-600 transition-all duration-500 h-full sm:h-full sm:w-full" style="width: {{ ($activeIndex / 3) * 100 }}%"></div>
                        </div>

                        <div class="relative grid grid-cols-1 gap-6 sm:grid-cols-4 sm:gap-0">
                            @foreach($stages as $index => $stage)
                                @php
                                    $isCompleted = $index < $activeIndex;
                                    $isActive = $index === $activeIndex;
                                    $isPending = $index > $activeIndex;
                                @endphp
                                <div class="flex items-start gap-4 sm:flex-col sm:items-center sm:text-center sm:gap-2">
                                    <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-300 z-10
                                        @if($isCompleted) bg-green-600 border-green-600 text-white shadow-sm @endif
                                        @if($isActive) bg-white border-green-600 text-green-600 shadow-md ring-4 ring-green-50 animate-pulse @endif
                                        @if($isPending) bg-white border-gray-200 text-gray-400 @endif
                                    ">
                                        @if($isCompleted)
                                            <x-heroicon-s-check class="h-6 w-6" />
                                        @else
                                            @if($stage['key'] === 'placed')
                                                <x-heroicon-o-document-text class="h-5 w-5" />
                                            @elseif($stage['key'] === 'preparing')
                                                <x-heroicon-o-cog-6-tooth class="h-5 w-5" />
                                            @elseif($stage['key'] === 'shipping')
                                                <x-heroicon-o-truck class="h-5 w-5" />
                                            @elseif($stage['key'] === 'delivered')
                                                <x-heroicon-o-check-badge class="h-5 w-5" />
                                            @endif
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold {{ $isActive ? 'text-green-600' : ($isCompleted ? 'text-gray-900' : 'text-gray-400') }}">
                                            {{ $stage['label'] }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-0.5 sm:max-w-[140px] sm:mx-auto">
                                            {{ $stage['desc'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Tracking History / Activity Log --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-4 mb-5">Activity Log</h3>
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @if($status !== 'cancelled')
                            @php
                                $placedTime = $order->created_at;
                                $confirmTime = $placedTime->copy()->addMinutes(8);
                                $shippedTime = $placedTime->copy()->addMinutes(18);
                                $deliveredTime = $order->receive_date ? \Carbon\Carbon::parse($order->receive_date) : $placedTime->copy()->addMinutes(32);
                                $completedTime = $order->updated_at;
                            @endphp

                            {{-- Checkout Event --}}
                            <li>
                                <div class="relative pb-8">
                                    @if(in_array($status, ['to_ship', 'shipped', 'delivered', 'completed']))
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white ring-8 ring-white">
                                                <x-heroicon-s-shopping-bag class="h-4 w-4" />
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">Checkout completed & order placed</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Order total ₱{{ number_format($order->order_total, 2) }} authorized via {{ strtoupper($order->paymentTransaction->payment_method ?? 'COD') }}</p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                <time>{{ $placedTime->format('h:i A') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            {{-- Preparing Event --}}
                            @if(in_array($status, ['to_ship', 'shipped', 'delivered', 'completed']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(in_array($status, ['shipped', 'delivered', 'completed']))
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white ring-8 ring-white">
                                                    <x-heroicon-s-check-circle class="h-4 w-4" />
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Order confirmed by vendor</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Inventory deducted and items packaged</p>
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time>{{ $confirmTime->format('h:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            {{-- Shipped Event --}}
                            @if(in_array($status, ['shipped', 'delivered', 'completed']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(in_array($status, ['delivered', 'completed']))
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white ring-8 ring-white">
                                                    <x-heroicon-s-truck class="h-4 w-4" />
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">In Transit: Parcel picked up by courier</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Rider en route on scooter (Tracking No: {{ $order->tracking_number }})</p>
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time>{{ $shippedTime->format('h:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            {{-- Delivered Event --}}
                            @if(in_array($status, ['delivered', 'completed']))
                                <li>
                                    <div class="relative pb-8">
                                        @if($status === 'completed')
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white ring-8 ring-white">
                                                    <x-heroicon-s-home class="h-4 w-4" />
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Delivered to residence</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Left securely at dorm entrance/room door</p>
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time>{{ $deliveredTime->format('h:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            {{-- Completed Event --}}
                            @if($status === 'completed')
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white ring-8 ring-white">
                                                    <x-heroicon-s-check class="h-4 w-4" />
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Order Completed</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Receipt confirmed by customer</p>
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time>{{ $completedTime->format('h:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @else
                            {{-- Cancelled event log --}}
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white ring-8 ring-white">
                                                <x-heroicon-s-shopping-bag class="h-4 w-4" />
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">Checkout completed & order placed</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Order total ₱{{ number_format($order->order_total, 2) }} authorized via {{ strtoupper($order->paymentTransaction->payment_method ?? 'COD') }}</p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                <time>{{ $order->created_at->format('h:i A') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white ring-8 ring-white">
                                                <x-heroicon-s-x-circle class="h-4 w-4" />
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold text-gray-950">Order Cancelled</p>
                                                <p class="text-xs text-red-600 mt-0.5 font-medium">Cancellation requested by customer</p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                <time>{{ $order->updated_at->format('h:i A') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Order Summary Details --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">Order Items</h3>
                <div class="divide-y divide-gray-100">
                    @foreach ($order->items as $product)
                        <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50">
                                    @if ($product->images->first())
                                        <img src="{{ asset($product->images->first()->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-gray-300">
                                            <x-heroicon-o-photo class="h-8 w-8" />
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">Quantity: {{ $product->pivot->quantity }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-900">₱{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Price Breakdown --}}
                <div class="bg-gray-50/70 rounded-xl p-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Items Subtotal</span>
                        <span>₱{{ number_format($order->order_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery Fee</span>
                        <span class="text-green-600 font-medium">FREE</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200/60 pt-2 mt-2">
                        <span>Total Paid</span>
                        <span>₱{{ number_format($order->order_total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Address and Payment Details Card --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">Delivery Address</h4>
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->username }}</p>
                    <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">
                        {{ $order->address->dorm_name ?? 'Dormitory Residence' }}<br>
                        Room {{ $order->address->room_number ?? 'N/A' }}<br>
                        {{ $order->address->street ?? 'Campus St' }}, {{ $order->address->city ?? 'Campus City' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">Payment Info</h4>
                    <div class="flex items-center gap-2.5">
                        <div class="rounded-lg bg-green-50 p-2 text-green-600">
                            <x-heroicon-o-credit-card class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ strtoupper($order->paymentTransaction->payment_method ?? 'COD') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Transaction status: <span class="font-semibold text-green-600 capitalize">{{ $order->paymentTransaction->transaction_status ?? 'Success' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Interactive Leaflet Campus Map (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col h-full min-h-[500px]">
                
                {{-- Telemetry bar overlay style --}}
                <div class="bg-gray-900 px-6 py-4 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-green-500"></span>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold">Campus Express GPS</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Simulated real-time location tracking</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($status === 'shipped')
                            <span class="inline-flex items-center rounded bg-yellow-400/20 px-2.5 py-0.5 text-xs font-semibold text-yellow-400 uppercase tracking-wider">
                                Rider Active
                            </span>
                        @elseif(in_array($status, ['delivered', 'completed']))
                            <span class="inline-flex items-center rounded bg-green-400/20 px-2.5 py-0.5 text-xs font-semibold text-green-400 uppercase tracking-wider">
                                Arrived
                            </span>
                        @else
                            <span class="inline-flex items-center rounded bg-gray-400/20 px-2.5 py-0.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Idle
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Map Container --}}
                <div id="map" class="flex-1 w-full bg-gray-50 h-[380px] z-10"></div>

                {{-- Map Telemetry Details Footer --}}
                <div class="p-5 border-t border-gray-100 bg-gray-50/50 space-y-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Delivery Route</span>
                        <span class="font-semibold text-gray-800">Area 2 ➔ Kalayaan Residence Hall</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Estimated Time</span>
                        <span class="font-semibold text-green-600">
                            @if($status === 'pending') Wait Confirm
                            @elseif($status === 'to_ship') 15-20 mins
                            @elseif($status === 'shipped') 5-10 mins (In Transit)
                            @else Arrived
                            @endif
                        </span>
                    </div>
                </div>
                {{-- Mock Map Disclaimer Banner --}}
                <div class="bg-amber-50 border-t border-amber-200/50 px-5 py-3 text-center text-xs text-amber-700 font-medium rounded-b-2xl">
                    ⚠️ <strong>Notice:</strong> The GPS tracking map shown above is a simulated campus express route for demonstration purposes.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Set coordinates for University Campus (UP Diliman representation)
        const vendorCoord = [14.6582, 121.0691]; // Shop at Area 2
        const customerCoord = [14.6534, 121.0652]; // Kalayaan Hall
        
        // Define Street Path
        const routePath = [
            vendorCoord,
            [14.6575, 121.0680],
            [14.6560, 121.0670],
            [14.6548, 121.0658],
            customerCoord
        ];

        // Initialize Map
        const map = L.map('map', {
            center: [14.6558, 121.0671],
            zoom: 15,
            zoomControl: false
        });

        // Add beautiful Positron theme layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
        }).addTo(map);

        // Add Zoom Control at bottom right
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Styled Custom Markers via Tailwind (avoiding broken local assets)
        const shopIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="flex items-center justify-center w-8 h-8 bg-green-600 rounded-full border-2 border-white shadow-lg text-white">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const dormIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full border-2 border-white shadow-lg text-white">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const courierIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full border-2 border-white shadow-xl text-white animate-pulse">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        // Place Main Shop and Home Markers
        L.marker(vendorCoord, { icon: shopIcon }).addTo(map).bindPopup("<b class='text-green-600 font-bold'>DormDash Shop</b><br>Area 2 Campus Vendor").openPopup();
        L.marker(customerCoord, { icon: dormIcon }).addTo(map).bindPopup("<b>My Dorm Residence</b><br>{{ $order->address->dorm_name ?? 'Kalayaan Hall' }}");

        // Draw Route
        const routeLine = L.polyline(routePath, {
            color: '#16a34a',
            weight: 4,
            opacity: 0.75,
            dashArray: '8, 8',
            lineJoin: 'round'
        }).addTo(map);

        // Courier Animation Logic
        const status = "{{ $status }}";
        let courierMarker;

        // Animate Courier scooter on path loop if shipped, otherwise place static
        if (status === 'shipped') {
            // Function to smoothly interpolate between path coordinates
            function getInterpolatedPoints(route, stepsPerSegment) {
                let points = [];
                for (let i = 0; i < route.length - 1; i++) {
                    let start = route[i];
                    let end = route[i+1];
                    for (let j = 0; j < stepsPerSegment; j++) {
                        let t = j / stepsPerSegment;
                        let lat = start[0] + (end[0] - start[0]) * t;
                        let lng = start[1] + (end[1] - start[1]) * t;
                        points.push([lat, lng]);
                    }
                }
                points.push(route[route.length - 1]);
                return points;
            }

            const animationPoints = getInterpolatedPoints(routePath, 60);
            courierMarker = L.marker(vendorCoord, { icon: courierIcon }).addTo(map).bindPopup("<b>Courier on Scooter</b><br>En route to your dorm...");

            let currentIndex = 0;
            function animateRider() {
                if (currentIndex >= animationPoints.length) {
                    currentIndex = 0; // Restart simulated path
                }
                courierMarker.setLatLng(animationPoints[currentIndex]);
                currentIndex++;
                setTimeout(animateRider, 120); // Smooth update every 120ms
            }
            animateRider();

        } else if (status === 'delivered' || status === 'completed') {
            // At Destination
            courierMarker = L.marker(customerCoord, { icon: courierIcon }).addTo(map).bindPopup("<b>Courier arrived</b><br>Delivered at your dorm!");
        } else if (status !== 'cancelled') {
            // Idle at Vendor
            courierMarker = L.marker(vendorCoord, { icon: courierIcon }).addTo(map).bindPopup("<b>Courier Waiting</b><br>Waiting for order packaging...");
        }

        // Fit map boundaries to include all coordinates
        map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
    });
</script>
@endsection
