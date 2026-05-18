@extends('layouts.vendor-main')

@section('title', 'Analytics - DormDash')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Analytics & Revenue</h1>
            <p class="mt-2 text-gray-600">Track your sales performance and revenue.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-md">
            <div class="p-8">
                <h1 class="mb-8 text-4xl font-bold">DormDash AI Analytics</h1>

                <div class="grid grid-cols-4 gap-6">
                    <div class="rounded-xl bg-white p-6 shadow">
                        <h2 class="text-gray-500">Predicted Revenue</h2>

                        <p class="text-3xl font-bold text-green-600">
                            ₱{{ number_format($forecast['predicted_revenue'] ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow">
                        <h2 class="text-gray-500">Low Stock Alerts</h2>

                        <p class="text-3xl font-bold text-red-500">
                            {{ is_array($inventory) ? count($inventory) : 0 }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 rounded-xl bg-white p-6 shadow">
                    <canvas id="salesChart"></canvas>
                </div>

                <div class="mt-8 rounded-xl bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">Inventory Alerts</h2>

                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th>Item</th>
                                <th>Stock</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($inventory as $item)
                                <tr class="border-b">
                                    <td class="py-2">
                                        {{ $item['name'] }}
                                    </td>

                                    <td class="py-2 text-red-500">
                                        {{ $item['stock'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                const historical = @json($forecast['historical']);

                const ctx = document.getElementById('salesChart');

                new Chart(ctx, {
                    type: 'line',

                    data: {
                        labels: historical.map((h) => h.order_date),

                        datasets: [
                            {
                                label: 'Revenue',
                                data: historical.map((h) => h.revenue),
                                borderColor: '#2563eb',
                                tension: 0.4,
                            },
                        ],
                    },
                });
            </script>
        </div>
    </div>
@endsection
