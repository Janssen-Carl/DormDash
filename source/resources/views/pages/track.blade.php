@extends('layouts.main')

@section('title', 'Track Order')

@section('content')
<div class="mx-auto max-w-4xl px-8 py-16 text-center">
    <div class="rounded-2xl border border-gray-100 bg-white p-12 shadow-sm">
        <x-heroicon-o-truck class="mx-auto h-16 w-16 text-green-500 mb-6" />
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Real-Time Tracking</h1>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Live order tracking is currently under development. For now, please check the status of your order on your orders page.
        </p>
        <a href="/orders" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-green-700 shadow-sm">
            Back to Orders
        </a>
    </div>
</div>
@endsection
