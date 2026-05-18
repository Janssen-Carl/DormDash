@extends('layouts.main')

@section('title', 'Analytics')

@section('content')
<div class="mx-auto max-w-6xl px-8 py-12">
    <div class="mb-8 flex items-center gap-4">
        <a href="/orders-overview" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-900">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Spending Analytics</h1>
            <p class="mt-1 text-sm text-gray-500">Track your total spending over the last 6 months.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
        <div class="h-96 w-full">
            <canvas id="spendingChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('spendingChart').getContext('2d');
        
        const labels = {!! json_encode($labels) !!};
        const data = {!! json_encode($data) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Amount Spent (₱)',
                    data: data,
                    borderColor: '#16a34a', // green-600
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#16a34a',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            },
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { size: 12 }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection
