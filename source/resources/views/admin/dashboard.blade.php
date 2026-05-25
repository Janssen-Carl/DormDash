@extends('layouts.admin-main')

@section('title', 'Dashboard ')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Platform overview and activity')

@section('content')
<div class="space-y-6">

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Total Accounts --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Accounts</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $totalUsers }}</p>
                <p class="text-xs text-emerald-600 mt-1 font-medium">+{{ $newUsersThisWeek }} this week</p>
            </div>
        </div>

        {{-- Active Vendors --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Active Vendors</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $activeVendors }}</p>
            </div>
        </div>

        {{-- Total Customers --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Customers</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $totalCustomers }}</p>
            </div>
        </div>

        {{-- Pending Approvals --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Pending Approvals</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $pendingVendors }}</p>
                @if($pendingVendors > 0)
                    <a href="{{ route('admin.accounts', ['role' => 'vendor', 'status' => 'pending']) }}"
                       class="text-xs text-amber-600 hover:text-amber-700 mt-1 inline-block font-medium underline underline-offset-2">
                        Review now →
                    </a>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Chart + Breakdown ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Registrations Chart --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">New Registrations</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Last 14 days</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Live</span>
            </div>
            <div class="relative h-52">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        {{-- Account Breakdown --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 mb-5">Account Breakdown</h2>
            <div class="relative h-40 flex items-center justify-center mb-5">
                <canvas id="breakdownChart"></canvas>
            </div>
            <div class="space-y-3 mt-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-sm text-gray-600">Vendors</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $totalVendors }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-sky-400 inline-block"></span>
                        <span class="text-sm text-gray-600">Customers</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $totalCustomers }}</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Total</span>
                    <span class="text-sm font-bold text-gray-900">{{ $totalUsers }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Admin Activity ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Recent Admin Activity</h2>
            <a href="{{ route('admin.logs') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">View all →</a>
        </div>

        @if($recentLogs->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400">No admin activity yet</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentLogs as $log)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4 min-w-0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $log->action_color }} flex-shrink-0">
                                {{ $log->action_label }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $log->target_username }}</p>
                                <p class="text-xs text-gray-400">by {{ $log->admin->username ?? 'Admin' }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0 ml-4">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Registrations line chart
    const regCtx = document.getElementById('registrationChart');
    if (regCtx) {
        new Chart(regCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'New Users',
                    data: @json($chartData),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1, font: { size: 11 }, color: '#9ca3af' } }
                }
            }
        });
    }

    // Breakdown doughnut chart
    const bdCtx = document.getElementById('breakdownChart');
    if (bdCtx) {
        new Chart(bdCtx, {
            type: 'doughnut',
            data: {
                labels: ['Vendors', 'Customers'],
                datasets: [{
                    data: [{{ $totalVendors }}, {{ $totalCustomers }}],
                    backgroundColor: ['#10b981', '#38bdf8'],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

});
</script>
@endpush
