@extends('layouts.vendor-main')

@section('title', 'Analytics - DormDash')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Analytics & Revenue</h1>
            <p class="mt-2 text-gray-600">Track your sales performance and revenue projections.</p>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-6">
            <div class="rounded-xl bg-white p-6 shadow">
                <h3 class="text-sm text-gray-500">Predicted Next Day</h3>
                <p class="text-2xl font-bold text-green-600">₱{{ number_format($summary['predicted_next_day'] ?? ($forecast['predicted'][0] ?? 0), 2) }}</p>
                <p class="text-xs text-gray-500">Last day: ₱{{ number_format($summary['last_day'] ?? 0, 2) }}</p>
                <p class="mt-2 {{ (($summary['percent_change'] ?? 0) >= 0) ? 'text-green-600' : 'text-red-600' }} text-sm">
                    {{ ($summary['percent_change'] ?? 0) }}% vs last day
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h3 class="text-sm text-gray-500">Low Stock Alerts</h3>
                <p class="text-2xl font-bold text-red-500">{{ is_array($inventory) ? count($inventory) : 0 }}</p>
                <p class="text-xs text-gray-500">Items below threshold</p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h3 class="text-sm text-gray-500">Top 5 Items (Next 7 days)</h3>
                <ol class="list-decimal pl-5 text-sm">
                    @foreach($itemsForecast as $it)
                        <li>Item {{ $it['item_id'] }} — ₱{{ number_format($it['total_predicted'] ?? (array_sum($it['predicted'] ?? [])), 2) }}</li>
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow mb-6">
            <canvas id="salesChart"></canvas>
        </div>

        <div class="rounded-xl bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-bold">Item Forecast (stacked)</h2>
            <canvas id="itemsChart"></canvas>
        </div>

        <div class="mt-6 rounded-xl bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-bold">Inventory Alerts</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Item</th>
                        <th class="py-2">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventory as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item['name'] ?? $item['item'] ?? 'Item' }}</td>
                            <td class="py-2 text-red-600">{{ $item['stock'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        const forecast = @json($forecast ?? null);
        const items = @json($itemsForecast ?? []);

        // Sales chart (historical + predicted)
        const labels = (forecast && forecast.historical)
            ? forecast.historical.map(h => h.order_date).concat(forecast.dates)
            : (forecast?.dates ?? []);

        const historicalData = (forecast && forecast.historical)
            ? forecast.historical.map(h => h.revenue)
            : [];

        const predicted = (forecast && forecast.predicted) ? forecast.predicted : [];

        const salesData = historicalData.concat(predicted);

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: salesData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    tension: 0.3
                }]
            }
        });

        // Items stacked bar chart
        const itemLabels = forecast?.dates ?? [];
        const datasets = items.map((it, idx) => ({
            label: 'Item ' + it.item_id,
            data: it.predicted,
            backgroundColor: ['#34D399','#60A5FA','#F59E0B','#F97316','#EF4444'][idx % 5]
        }));

        new Chart(document.getElementById('itemsChart'), {
            type: 'bar',
            data: {
                labels: itemLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: { x: { stacked: true }, y: { stacked: true } }
            }
        });
    </script>
@endsection
