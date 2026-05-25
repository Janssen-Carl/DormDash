@extends('layouts.admin-main')

@section('title', 'Platform Insights — DormDash')
@section('page-title', 'Platform Insights')
@section('page-subtitle', 'Global performance analytics, sales trends, and product data')

@section('content')
<div class="space-y-6">

    {{-- ── Segmented Controls & Action Bar ── --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-sm font-semibold text-gray-800">Analytical View Filter</h2>
            <p class="text-xs text-gray-400 mt-0.5">Toggle timeframe to analyze platform velocity and forecast trends</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Timeframe filter --}}
            <div class="inline-flex p-1 bg-gray-100 rounded-xl">
                <a href="?timeframe=7" 
                   class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-150 {{ $timeframe === '7' ? 'bg-white text-emerald-600 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-900' }}">
                    Last 7 Days
                </a>
                <a href="?timeframe=30" 
                   class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-150 {{ $timeframe === '30' ? 'bg-white text-emerald-600 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-900' }}">
                    Last 30 Days
                </a>
                <a href="?timeframe=all" 
                   class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-150 {{ $timeframe === 'all' ? 'bg-white text-emerald-600 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-900' }}">
                    All Time
                </a>
            </div>

            {{-- Export CSV button --}}
            <a href="{{ route('admin.insights.export', ['timeframe' => $timeframe]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        
        {{-- Card 1: Revenue --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between group hover:border-emerald-500 hover:shadow-md transition-all duration-200 cursor-default">
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Sales Revenue</p>
                <h3 class="text-3xl font-extrabold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</h3>
                <div class="flex items-center gap-1.5">
                    @if($revenueGrowth >= 0)
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format($revenueGrowth, 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($revenueGrowth), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-medium">vs prev period</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Card 2: Orders Count --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between group hover:border-indigo-500 hover:shadow-md transition-all duration-200 cursor-default">
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Order Volume</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($ordersCount) }}</h3>
                <div class="flex items-center gap-1.5">
                    @if($ordersGrowth >= 0)
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format($ordersGrowth, 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($ordersGrowth), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-medium">vs prev period</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Card 3: Average Order Value --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between group hover:border-amber-500 hover:shadow-md transition-all duration-200 cursor-default">
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Average Order Value</p>
                <h3 class="text-3xl font-extrabold text-gray-900">₱{{ number_format($averageOrderValue, 2) }}</h3>
                <div class="flex items-center gap-1.5">
                    @if($aovGrowth >= 0)
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format($aovGrowth, 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($aovGrowth), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-medium">vs prev period</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- Card 4: Items Sold --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between group hover:border-sky-500 hover:shadow-md transition-all duration-200 cursor-default">
            <div class="space-y-2">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Products / Items Sold</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($totalItemsSold) }}</h3>
                <div class="flex items-center gap-1.5">
                    @if($itemsGrowth >= 0)
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format($itemsGrowth, 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($itemsGrowth), 1) }}%
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-medium">vs prev period</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-500 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ── AI Forecasting Section ── --}}
    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-2xl p-6 shadow-lg border border-indigo-950 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="space-y-4 max-w-md">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500 text-white uppercase tracking-wider animate-pulse">AI Engine</span>
                    @if($aiOnline)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-400">
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span> Offline / Fallback
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-bold tracking-tight">Global AI-Powered Sales Forecast</h3>
                    <p class="text-xs text-indigo-200 mt-1">Linear regression forecasting using active platform database patterns</p>
                </div>

                @if($aiOnline)
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="p-3 bg-white/5 border border-white/10 rounded-xl">
                            <p class="text-[10px] text-indigo-300 font-semibold uppercase tracking-wider">Next-Day Forecasted Revenue</p>
                            <h4 class="text-xl font-extrabold text-white mt-1">₱{{ number_format($predictedNextDay, 2) }}</h4>
                        </div>
                        <div class="p-3 bg-white/5 border border-white/10 rounded-xl">
                            <p class="text-[10px] text-indigo-300 font-semibold uppercase tracking-wider">Velocity Growth Index</p>
                            <h4 class="text-xl font-extrabold mt-1 {{ $predictedGrowth >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $predictedGrowth >= 0 ? '+' : '' }}{{ number_format($predictedGrowth, 1) }}%
                            </h4>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-300 rounded-xl text-xs flex items-start gap-2.5">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <span class="font-bold">AI Forecasting Service Offline</span>
                            <p class="mt-0.5 text-indigo-200">Start the Python FastAPI forecasting service (`python -m uvicorn app.AI.main:app --port 5000`) to enable active machine-learning analytics.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex-1 w-full lg:max-w-xl h-48 md:h-52 bg-white/5 border border-white/10 p-4 rounded-xl flex items-center justify-center relative">
                @if($aiOnline)
                    <canvas id="forecastChart" class="w-full h-full"></canvas>
                @else
                    <div class="text-center text-xs text-indigo-300 flex flex-col items-center gap-2">
                        <svg class="w-8 h-8 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                        </svg>
                        <span>Awaiting AI connection...</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Core Graph Section: Revenue Trend & Vendor Contribution ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        
        {{-- Revenue & Order Trend (Chart) --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Revenue & Order Volume Trend</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Platform timeline tracking for sales and checkout counts</p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Real-Time Data</span>
            </div>
            <div class="relative h-64 md:h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Vendor Contribution Chart --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="mb-4">
                <h3 class="text-base font-semibold text-gray-900">Revenue share by Vendor</h3>
                <p class="text-xs text-gray-400 mt-0.5">Vendor distribution based on total item sales</p>
            </div>
            <div class="relative h-44 flex items-center justify-center">
                @if($vendorPerformance->isEmpty() || $vendorPerformance->sum('total_revenue') == 0)
                    <div class="text-center text-xs text-gray-400">No vendor revenue recorded in this period</div>
                @else
                    <canvas id="vendorPieChart"></canvas>
                @endif
            </div>
            <div class="space-y-2 mt-4 max-h-36 overflow-y-auto pr-1">
                @php
                    $totalVRevenue = $vendorPerformance->sum('total_revenue') ?: 1;
                @endphp
                @foreach($vendorPerformance->take(4) as $index => $vendor)
                    @php
                        $percentage = ($vendor->total_revenue / $totalVRevenue) * 100;
                        $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'];
                        $currentColor = $colors[$index % count($colors)];
                    @endphp
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 truncate">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $currentColor }};"></span>
                            <span class="text-gray-600 truncate font-medium">{{ $vendor->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">₱{{ number_format($vendor->total_revenue, 0) }}</span>
                            <span class="text-gray-400 text-[10px] font-medium">({{ number_format($percentage, 1) }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── Visual Trends: Top Products Bar & Order Statuses ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Top Selling Products Bar Chart --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Top Selling Products</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Quantity-sold leaderboard for the best items</p>
                </div>
            </div>
            <div class="relative h-64 md:h-72">
                @if($topProducts->isEmpty())
                    <div class="flex items-center justify-center h-full text-sm text-gray-400">No items sold in this period</div>
                @else
                    <canvas id="productsBarChart"></canvas>
                @endif
            </div>
        </div>

        {{-- Order Status Breakdown --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-900">Order Status Distribution</h3>
                <p class="text-xs text-gray-400 mt-0.5">Visual ratio of current statuses in active timeframe</p>
            </div>
            <div class="relative h-48 flex items-center justify-center my-3">
                <canvas id="statusPolarChart"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center text-[10px] font-semibold text-gray-500 mt-2">
                <div class="p-1 bg-amber-50 text-amber-700 rounded-lg border border-amber-100">
                    <div class="text-xs font-bold">{{ $ordersByStatus['pending'] }}</div>
                    Pending
                </div>
                <div class="p-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                    <div class="text-xs font-bold">{{ $ordersByStatus['shipped'] + $ordersByStatus['to_ship'] }}</div>
                    In Transit
                </div>
                <div class="p-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                    <div class="text-xs font-bold">{{ $ordersByStatus['completed'] + $ordersByStatus['delivered'] }}</div>
                    Delivered
                </div>
            </div>
        </div>

    </div>

    {{-- ── Stacked Full-Width Leaderboards ── --}}
    <div class="space-y-6">

        {{-- 1. Vendor Performance Leaderboard (Separated Full Width) --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Vendor Sales Performance</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Overall vendor statistics ranked by revenue contribution</p>
                </div>
                <span class="text-xs px-2.5 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-700 font-semibold rounded-lg">Global Rankings</span>
            </div>

            @if($vendorPerformance->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No vendor sales recorded</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3.5">Rank</th>
                                <th class="px-6 py-3.5">Vendor</th>
                                <th class="px-6 py-3.5 text-center">Total Orders</th>
                                <th class="px-6 py-3.5 text-center">Units Sold</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs">
                            @foreach($vendorPerformance as $index => $vendor)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3.5 font-bold text-gray-900 text-sm">{{ $vendor->name }}</td>
                                    <td class="px-6 py-3.5 text-center font-semibold text-gray-700">{{ number_format($vendor->total_orders) }}</td>
                                    <td class="px-6 py-3.5 text-center text-gray-500 font-medium">{{ number_format($vendor->total_items_sold) }}</td>
                                    <td class="px-6 py-3.5">
                                        @if($vendor->active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-extrabold text-emerald-600 text-sm">₱{{ number_format($vendor->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- 2. Product Sales Leaderboard (Separated Full Width) --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Product Sales Leaderboard</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Top performing products ranked by total volume</p>
                </div>
                <span class="text-xs px-2.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-700 font-semibold rounded-lg">Platform Bestsellers</span>
            </div>
            
            @if($topProducts->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No product sales record</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3.5">Rank</th>
                                <th class="px-6 py-3.5">Product</th>
                                <th class="px-6 py-3.5">Vendor</th>
                                <th class="px-6 py-3.5 text-center">Units Sold</th>
                                <th class="px-6 py-3.5">Stock Left</th>
                                <th class="px-6 py-3.5 text-right">Revenue Generated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs">
                            @foreach($topProducts as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3.5 font-medium text-gray-900">
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium">{{ $item->brand ?? 'No Brand' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-500 font-semibold">{{ $item->vendor_name }}</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-gray-800 text-sm">{{ number_format($item->total_sold) }}</td>
                                    <td class="px-6 py-3.5">
                                        @if($item->stock == 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">Out of stock</span>
                                        @elseif($item->stock < 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">Low Stock ({{ $item->stock }})</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">{{ $item->stock }} in stock</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-extrabold text-emerald-600 text-sm">₱{{ number_format($item->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Recent Transactions ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Recent Platform Transactions</h3>
            <p class="text-xs text-gray-400 mt-0.5">Real-time audit log of the 10 most recent orders placed on the application</p>
        </div>

        @if($recentOrders->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-sm text-gray-400">No orders placed on the platform yet</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-3.5">Order ID</th>
                            <th class="px-6 py-3.5">Date</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Purchased Items & Vendors</th>
                            <th class="px-6 py-3.5 text-center">Payment</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-gray-800">#ORD-{{ $order->order_id }}</td>
                                <td class="px-6 py-3.5 text-gray-500 font-medium">
                                    {{ $order->created_at->format('M d, Y') }}
                                    <span class="block text-[10px] text-gray-400 mt-0.5">{{ $order->created_at->format('H:i A') }}</span>
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-gray-900">
                                    @if($order->customer)
                                        {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                        <span class="block text-[10px] text-gray-400 font-medium">{{ $order->customer->user->email ?? 'no email' }}</span>
                                    @else
                                        Deleted User
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 max-w-[280px]">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($order->items as $item)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 text-[10px] font-semibold cursor-default transition-colors"
                                                  title="{{ $item->name }} — sold by {{ $item->vendor->name ?? 'Unknown' }}">
                                                {{ Str::limit($item->name, 16) }} (x{{ $item->pivot->quantity }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @php
                                        $method = $order->paymentTransaction->payment_method ?? 'N/A';
                                        $pStatus = $order->paymentTransaction->status ?? 'pending';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $method === 'cod' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-blue-50 text-blue-700 border-blue-200' }} uppercase">
                                        {{ $method }}
                                    </span>
                                    <span class="block text-[9px] font-bold mt-1 {{ $pStatus === 'success' ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ strtoupper($pStatus) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    @switch($order->order_status)
                                        @case('completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Completed</span>
                                            @break
                                        @case('delivered')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Delivered</span>
                                            @break
                                        @case('shipped')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Shipped</span>
                                            @break
                                        @case('to_ship')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Ready to Ship</span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Cancelled</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-3.5 text-right font-bold text-slate-900 text-sm">
                                    ₱{{ number_format($order->order_total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 1. Dual-Axis Sales & Orders Trend Line Chart
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Revenue (₱)',
                        data: @json($chartRevenueData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        borderWidth: 2,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders Count',
                        data: @json($chartOrderData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.2,
                        borderDash: [5, 5],
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 10, weight: '500' },
                            color: '#4b5563'
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 11 },
                        usePointStyle: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#9ca3af' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            font: { size: 10 },
                            color: '#9ca3af',
                            callback: function(value) { return '₱' + value.toLocaleString(); }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 },
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
    }

    // 2. Vendor Revenue Share Doughnut Chart
    const vendorCtx = document.getElementById('vendorPieChart');
    if (vendorCtx) {
        const vendorNames = @json($vendorPerformance->take(6)->pluck('name'));
        const vendorRevenues = @json($vendorPerformance->take(6)->pluck('total_revenue'));
        
        new Chart(vendorCtx, {
            type: 'doughnut',
            data: {
                labels: vendorNames,
                datasets: [{
                    data: vendorRevenues,
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 3. Top Products Horizontal Bar Chart
    const prodCtx = document.getElementById('productsBarChart');
    if (prodCtx) {
        const productNames = @json($topProducts->pluck('name'));
        const productQuantities = @json($topProducts->pluck('total_sold'));
        
        new Chart(prodCtx, {
            type: 'bar',
            data: {
                labels: productNames.map(name => name.length > 20 ? name.substring(0, 18) + '..' : name),
                datasets: [{
                    label: 'Units Sold',
                    data: productQuantities,
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    hoverBackgroundColor: '#3b82f6',
                    borderRadius: 6,
                    borderWidth: 0,
                    barPercentage: 0.6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 10 }, color: '#9ca3af', stepSize: 1 }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#4b5563' }
                    }
                }
            }
        });
    }

    // 4. Order Status Polar Area Chart
    const statusCtx = document.getElementById('statusPolarChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'polarArea',
            data: {
                labels: ['Pending', 'To Ship', 'Shipped', 'Delivered', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $ordersByStatus['pending'] }},
                        {{ $ordersByStatus['to_ship'] }},
                        {{ $ordersByStatus['shipped'] }},
                        {{ $ordersByStatus['delivered'] }},
                        {{ $ordersByStatus['completed'] }},
                        {{ $ordersByStatus['cancelled'] }}
                    ],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.75)',
                        'rgba(99, 102, 241, 0.75)',
                        'rgba(59, 130, 246, 0.75)',
                        'rgba(16, 185, 129, 0.75)',
                        'rgba(5, 150, 105, 0.75)',
                        'rgba(239, 68, 68, 0.75)'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    r: {
                        ticks: { display: false },
                        grid: { color: '#f3f4f6' }
                    }
                }
            }
        });
    }

    // 5. AI Sales Forecast Line Chart
    @if($aiOnline)
    const forecastCtx = document.getElementById('forecastChart');
    if (forecastCtx) {
        new Chart(forecastCtx, {
            type: 'line',
            data: {
                labels: @json($aiLabels),
                datasets: [{
                    label: 'Predicted Sales (₱)',
                    data: @json($aiRevenue),
                    borderColor: '#818cf8',
                    backgroundColor: 'rgba(129, 140, 248, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#818cf8',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 10 },
                        callbacks: {
                            label: function(context) {
                                return ' Predicted Revenue: ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9 }, color: '#c7d2fe' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.08)' },
                        ticks: {
                            font: { size: 9 },
                            color: '#c7d2fe',
                            callback: function(value) { return '₱' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    }
    @endif

});
</script>
@endpush
