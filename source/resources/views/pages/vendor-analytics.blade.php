@extends('layouts.vendor-main')

@section('title', 'Analytics - DormDash')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Analytics & Revenue</h1>
            <p class="mt-2 text-gray-600">Track your sales performance and revenue projections.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-emerald-50 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Predicted Next Day</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">
                    ₱{{ number_format($summary['predicted_next_day'] ?? ($forecast['predicted'][0] ?? 0), 2) }}</p>
                <div class="mt-2 flex items-center text-sm">
                    @php $pct = $summary['percent_change'] ?? 0; @endphp
                    @if($pct >= 0)
                        <span class="flex items-center text-emerald-600 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941">
                                </path>
                            </svg>
                            {{ $pct }}%
                        </span>
                    @else
                        <span class="flex items-center text-red-600 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181">
                                </path>
                            </svg>
                            {{ abs($pct) }}%
                        </span>
                    @endif
                    <span class="text-gray-500 ml-2">vs yesterday</span>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">7-Day Forecast</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($next7Total ?? 0, 2) }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    Predicted total for next 7 days
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Growth Insight</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format(abs($growth ?? 0), 1) }}%</p>
                <div class="mt-2 flex items-center text-sm">
                    @if(($growth ?? 0) >= 0)
                        <span class="text-emerald-600 font-medium mr-1">Increase</span>
                    @else
                        <span class="text-red-600 font-medium mr-1">Decrease</span>
                    @endif
                    <span class="text-gray-500">vs past 7 days</span>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2.25m0 4.5.01-.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-500">Low Stock Alerts</h3>
                </div>
                <p class="text-2xl font-bold text-red-600">{{ is_array($inventory) ? count($inventory) : 0 }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    Items below 10 units
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow mb-6">
            <h2 class="text-lg font-bold text-gray-900">Revenue Trend & Forecast</h2>
            <p class="mt-1 mb-4 text-sm text-gray-500">This chart tracks your daily sales. The first part shows what you've
                actually earned over the last 60 days. The extended part shows what our AI expects you to earn in the next 7
                days, based on your past performance. Use this to easily see if your sales are trending up or down!</p>
            <canvas id="salesChart"></canvas>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 rounded-xl bg-white p-6 shadow">
                <h2 class="mb-1 text-lg font-bold text-gray-900">Top Products — 7-Day Revenue Forecast</h2>
                <p class="mt-1 mb-4 text-sm text-gray-500">This chart shows which of your top 5 products will bring in the
                    most money over the next 7 days. Each color represents a different product. The taller the bar, the more
                    revenue is expected for that day. This helps you know exactly which items you need to keep fully
                    stocked!</p>
                <canvas id="itemsChart"></canvas>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h2 class="mb-1 text-lg font-bold text-gray-900">Revenue Distribution</h2>
                <p class="mt-1 mb-4 text-sm text-gray-500">Proportion of forecasted revenue driven by your top 5 items vs
                    the rest of your inventory.</p>
                <div class="relative w-full aspect-square mt-8">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center gap-2.5 mb-1">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Top 5 Performers</h2>
                        <p class="text-xs text-gray-500">Highest predicted revenue for the next 7 days</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-4">
                    @php
                        if (isset($itemsForecast) && is_array($itemsForecast)) {
                            usort($itemsForecast, function($a, $b) {
                                $totalA = $a['total_predicted'] ?? array_sum($a['predicted'] ?? []);
                                $totalB = $b['total_predicted'] ?? array_sum($b['predicted'] ?? []);
                                return $totalB <=> $totalA; // Descending order
                            });
                        }

                        $maxPredicted = 0;
                        if (isset($itemsForecast) && count($itemsForecast) > 0) {
                            $maxPredicted = max(array_map(function ($it) {
                                return $it['total_predicted'] ?? array_sum($it['predicted'] ?? []);
                            }, $itemsForecast));
                        }
                        $colors = ['bg-emerald-500', 'bg-blue-500', 'bg-purple-500', 'bg-amber-500', 'bg-orange-500'];
                    @endphp

                    @foreach($itemsForecast as $idx => $it)
                        @php
                            $total = $it['total_predicted'] ?? array_sum($it['predicted'] ?? []);
                            $percent = $maxPredicted > 0 ? ($total / $maxPredicted) * 100 : 0;
                            $color = $colors[$idx % count($colors)];
                        @endphp
                        <div class="flex items-center gap-4">
                            <div class="w-6 text-center text-sm font-bold text-gray-400">#{{ $idx + 1 }}</div>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span
                                        class="text-sm font-medium text-gray-800">{{ $it['name'] ?? ('Item ' . $it['item_id']) }}</span>
                                    <span class="text-sm font-bold text-gray-900">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2.5">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Inventory Alerts</h2>
                            <p class="text-xs text-gray-500">Products running low on stock (below 10 units)</p>
                        </div>
                    </div>
                    @if(is_array($inventory) && count($inventory) > 0)
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 ring-1 ring-inset ring-red-200">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            {{ count($inventory) }} alert{{ count($inventory) !== 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>

                @if(is_array($inventory) && count($inventory) > 0)
                    <div class="mt-4 grid gap-3">
                        @foreach($inventory as $item)
                                        @php
                                            $stock = $item['stock'] ?? 0;
                                            if ($stock <= 3) {
                                                $severity = 'critical';
                                                $badgeClasses = 'bg-red-100 text-red-700 ring-red-200';
                                                $barColor = 'bg-red-500';
                                                $iconBg = 'bg-red-50';
                                                $iconColor = 'text-red-500';
                                                $borderColor = 'border-red-100';
                                                $label = 'Critical';
                                            } elseif ($stock <= 6) {
                                                $severity = 'warning';
                                                $badgeClasses = 'bg-amber-100 text-amber-700 ring-amber-200';
                                                $barColor = 'bg-amber-500';
                                                $iconBg = 'bg-amber-50';
                                                $iconColor = 'text-amber-500';
                                                $borderColor = 'border-amber-100';
                                                $label = 'Warning';
                                            } else {
                                                $severity = 'low';
                                                $badgeClasses = 'bg-yellow-50 text-yellow-700 ring-yellow-200';
                                                $barColor = 'bg-yellow-400';
                                                $iconBg = 'bg-yellow-50';
                                                $iconColor = 'text-yellow-500';
                                                $borderColor = 'border-yellow-100';
                                                $label = 'Low';
                                            }
                                            $percent = min(($stock / 10) * 100, 100);
                                        @endphp
                              <div
                                            class="flex items-center gap-4 rounded-lg border {{ $borderColor }} bg-gray-50/50 px-4 py-3 transition-all duration-200 hover:shadow-sm hover:bg-white">
                                            {{-- Icon --}}
                                            <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-lg {{ $iconBg }}">
                                                @if($severity === 'critical')
                                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                    </svg>
                                                @elseif($severity === 'warning')
                                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                @endif
                                            </div>

                                            {{-- Product info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                                        {{ $item['name'] ?? $item['item'] ?? 'Unknown Item' }}</p>
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                                        {{ $label }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    {{-- Progress bar --}}
                                                    <div class="flex-1 h-2 rounded-full bg-gray-200 overflow-hidden">
                                                        <div class="h-full rounded-full {{ $barColor }} transition-all duration-500"
                                                            style="width: {{ $percent }}%"></div>
                                                    </div>
                                                    <span
                                                        class="flex-shrink-0 text-xs font-bold {{ $stock <= 3 ? 'text-red-600' : ($stock <= 6 ? 'text-amber-600' : 'text-yellow-600') }}">
                                                        {{ $stock }} left
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="mt-4 flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-200 bg-gray-50/50 py-10">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-600">All products are well-stocked</p>
                        <p class="text-xs text-gray-400 mt-1">No items below the 10-unit threshold</p>
                    </div>
                @endif
            </div>





        </div> <!-- end grid -->


    </div>


    <script>
        const forecast = @json($forecast ?? null);
        const items = @json($itemsForecast ?? []);
        const next7Total = {{ $next7Total ?? 0 }};

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
                    tension: 0.3,
                    segment: {
                        borderDash: ctx => ctx.p0DataIndex >= Math.max(0, historicalData.length - 1) ? [6, 6] : undefined,
                        borderColor: ctx => ctx.p0DataIndex >= Math.max(0, historicalData.length - 1) ? '#1500ffff' : '#10B981',
                    }
                }]
            }
        });

        // Items stacked bar chart
        const itemLabels = forecast?.dates ?? [];
        const bgColors = ['#10B981', '#3B82F6', '#A855F7', '#F59E0B', '#F97316'];

        const datasets = items.map((it, idx) => ({
            label: it.name || ('Item ' + it.item_id),
            data: it.predicted,
            backgroundColor: bgColors[idx % 5]
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

        // Doughnut Chart
        const top5Total = items.reduce((sum, it) => sum + (it.total_predicted || it.predicted.reduce((a, b) => a + b, 0)), 0);
        const othersTotal = Math.max(0, next7Total - top5Total);

        const doughnutData = items.map(it => it.total_predicted || it.predicted.reduce((a, b) => a + b, 0));
        doughnutData.push(othersTotal);

        const doughnutLabels = items.map(it => it.name || ('Item ' + it.item_id));
        doughnutLabels.push('Other Items');

        const doughnutColors = [...bgColors, '#E5E7EB'];

        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: doughnutColors,
                    borderWidth: 0



                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }



                },
                cutout: '70%'









            }
        });
    </script>
@endsection