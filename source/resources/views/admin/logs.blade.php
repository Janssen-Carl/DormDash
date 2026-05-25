@extends('layouts.admin-main')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Record of all admin actions on accounts')

@section('content')
<div class="space-y-5">

    {{-- ── Filter Bar ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <form id="filter-form" method="GET" action="{{ route('admin.logs') }}" class="flex flex-wrap items-center gap-3">

            {{-- Preserve sort state --}}
            <input type="hidden" name="sort" value="{{ $sortBy }}">
            <input type="hidden" name="dir" value="{{ $sortDir }}">

            {{-- Search --}}
            <div class="relative flex-1 min-w-52">
                <svg id="search-icon" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <svg id="search-spinner" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-emerald-500 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <input type="text" id="search-input" name="search" value="{{ $search }}"
                       placeholder="Search by target user or admin..."
                       autocomplete="off"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent" />
            </div>

            {{-- Action Filter --}}
            <select name="action" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white">
                <option value="all" {{ $actionFilter === 'all' ? 'selected' : '' }}>All Actions</option>
                @foreach($actionTypes as $type)
                    <option value="{{ $type }}" {{ $actionFilter === $type ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors">
                Filter
            </button>

            @if($search || $actionFilter !== 'all')
                <a href="{{ route('admin.logs') }}"
                   class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Clear
                </a>
            @endif

        </form>
    </div>

    {{-- ── Logs Table ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Table header bar with count + export --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <h2 class="text-sm font-semibold text-gray-700">{{ $logs->total() }} log entries</h2>

            {{-- Export CSV button --}}
            <a href="{{ route('admin.logs.export', array_merge(request()->only(['search', 'action']))) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 hover:bg-emerald-600 text-gray-600 hover:text-white border border-gray-200 hover:border-emerald-600 transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">

                        {{-- Sortable: Timestamp --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'created_at' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.logs', array_merge(request()->except(['sort','dir','page']), ['sort' => 'created_at', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'created_at' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                Timestamp
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortBy === 'created_at' && $sortDir === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    @elseif($sortBy === 'created_at' && $sortDir === 'desc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    @endif
                                </svg>
                            </a>
                        </th>

                        {{-- Sortable: Action --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'action' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.logs', array_merge(request()->except(['sort','dir','page']), ['sort' => 'action', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'action' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                Action
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortBy === 'action' && $sortDir === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    @elseif($sortBy === 'action' && $sortDir === 'desc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    @endif
                                </svg>
                            </a>
                        </th>

                        {{-- Sortable: Target Account --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'target_username' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.logs', array_merge(request()->except(['sort','dir','page']), ['sort' => 'target_username', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'target_username' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                Target Account
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortBy === 'target_username' && $sortDir === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    @elseif($sortBy === 'target_username' && $sortDir === 'desc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    @endif
                                </svg>
                            </a>
                        </th>

                        {{-- Static: Performed By --}}
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Performed By</th>

                        {{-- Static: Notes --}}
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Timestamp --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-gray-700 font-medium text-xs">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="text-gray-400 text-xs">{{ $log->created_at->format('h:i A') }}</p>
                                <p class="text-gray-300 text-xs">{{ $log->created_at->diffForHumans() }}</p>
                            </td>

                            {{-- Action Badge --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $log->action_color }}">
                                    {{ $log->action_label }}
                                </span>
                            </td>

                            {{-- Target Account --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0
                                        {{ $log->target_role === 'vendor' ? 'bg-emerald-500' : 'bg-sky-500' }}">
                                        {{ strtoupper(substr($log->target_username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $log->target_username }}</p>
                                        @if($log->target_role)
                                            <span class="text-xs text-gray-400">{{ ucfirst($log->target_role) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Performed By --}}
                            <td class="px-6 py-4">
                                @if($log->admin)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($log->admin->username, 0, 2)) }}
                                        </div>
                                        <span class="text-gray-700 text-xs font-medium">{{ $log->admin->username }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">Admin #{{ $log->admin_id }}</span>
                                @endif
                            </td>

                            {{-- Notes --}}
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-gray-500 text-xs truncate" title="{{ $log->notes }}">
                                    {{ $log->notes ?? '—' }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No log entries found</p>
                                    <p class="text-xs text-gray-400 mt-1">Admin actions will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
(function() {
    const form     = document.getElementById('filter-form');
    const input    = document.getElementById('search-input');
    const icon     = document.getElementById('search-icon');
    const spinner  = document.getElementById('search-spinner');
    const selects  = form.querySelectorAll('select');
    let timer      = null;

    // Keep search focused after refresh and place cursor at end
    if (input) {
        input.focus();
        var val = input.value;
        input.value = '';
        input.value = val;
    }

    function showSpinner() {
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    }

    // Live search with 600ms debounce
    input.addEventListener('input', function() {
        clearTimeout(timer);
        showSpinner();
        timer = setTimeout(function() {
            form.submit();
        }, 600);
    });

    // Auto-submit on dropdown change
    selects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            form.submit();
        });
    });
})();
</script>
@endpush
