@extends('layouts.vendor-main')

@section('title', 'Analytics - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analytics & Revenue</h1>
        <p class="mt-2 text-gray-600">Track your sales performance and revenue.</p>
    </div>

    @if(!isset($hasData) || !$hasData)
    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-md text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 text-indigo-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No analytics data</h3>
        <p class="text-gray-500 max-w-md mx-auto">Analytics and revenue charts will be generated once customers place orders for your products.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue (6 months)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        @if(!empty($statusBreakdown))
        @foreach($statusBreakdown as $status => $amount)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($amount, 2) }}</p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full
                    @if($status === 'pending') bg-yellow-50 text-yellow-600
                    @elseif($status === 'to_ship') bg-blue-50 text-blue-600
                    @elseif($status === 'shipped') bg-purple-50 text-purple-600
                    @elseif($status === 'delivered') bg-green-50 text-green-600
                    @elseif($status === 'completed') bg-emerald-50 text-emerald-600
                    @else bg-gray-50 text-gray-600
                    @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Monthly Revenue (Last 6 Months)</h3>
        <canvas id="revenueChart" width="400" height="100"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels ?? []) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($data ?? []) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif
</div>
@endsection
