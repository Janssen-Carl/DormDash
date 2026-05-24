@extends('layouts.admin-main')

@section('title', 'Account Management — Admin — DormDash')
@section('page-title', 'Account Management')
@section('page-subtitle', 'Manage vendor and customer accounts')

@section('content')
<div class="space-y-5">

    {{-- ── Filter Bar ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <form id="filter-form" method="GET" action="{{ route('admin.accounts') }}" class="flex flex-wrap items-center gap-3">

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
                       placeholder="Search by username or email..."
                       autocomplete="off"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent" />
            </div>

            {{-- Role Filter --}}
            <select name="role" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white">
                <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>All Roles</option>
                <option value="vendor" {{ $roleFilter === 'vendor' ? 'selected' : '' }}>Vendors</option>
                <option value="customer" {{ $roleFilter === 'customer' ? 'selected' : '' }}>Customers</option>
            </select>

            {{-- Status Filter --}}
            <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white">
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending Approval</option>
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors">
                Filter
            </button>

            @if($search || $roleFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('admin.accounts') }}"
                   class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Clear
                </a>
            @endif

        </form>
    </div>

    {{-- ── Summary badges ── --}}
    <div class="flex items-center gap-2 flex-wrap">
        <span class="text-sm text-gray-500">{{ $users->total() }} accounts found</span>
        @if($roleFilter !== 'all')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">{{ ucfirst($roleFilter) }}</span>
        @endif
        @if($statusFilter !== 'all')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">{{ ucfirst($statusFilter) }}</span>
        @endif
    </div>

    {{-- ── Accounts Table ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">

                        {{-- Sortable: User (username) --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'username' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.accounts', array_merge(request()->except(['sort','dir','page']), ['sort' => 'username', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'username' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                User
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortBy === 'username' && $sortDir === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    @elseif($sortBy === 'username' && $sortDir === 'desc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    @endif
                                </svg>
                            </a>
                        </th>

                        {{-- Sortable: Role --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'role' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.accounts', array_merge(request()->except(['sort','dir','page']), ['sort' => 'role', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'role' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                Role
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortBy === 'role' && $sortDir === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    @elseif($sortBy === 'role' && $sortDir === 'desc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    @endif
                                </svg>
                            </a>
                        </th>

                        {{-- Static: Status --}}
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>

                        {{-- Sortable: Joined --}}
                        <th class="px-6 py-3.5 text-left">
                            @php $nextDir = ($sortBy === 'created_at' && $sortDir === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ route('admin.accounts', array_merge(request()->except(['sort','dir','page']), ['sort' => 'created_at', 'dir' => $nextDir])) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider transition-colors {{ $sortBy === 'created_at' ? 'text-emerald-600' : 'text-gray-500 hover:text-emerald-600' }}">
                                Joined
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

                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            {{-- User Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0
                                        {{ $user->role === 'vendor' ? 'bg-emerald-600' : 'bg-sky-500' }}">
                                        {{ strtoupper(substr($user->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->username }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                    {{ $user->role === 'vendor' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($user->role === 'vendor')
                                    @if($user->vendor && $user->vendor->active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                                            Pending Approval
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-100 text-sky-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 inline-block"></span>
                                        Active
                                    </span>
                                @endif
                            </td>

                            {{-- Joined --}}
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ $user->created_at->format('M d, Y') }}
                                <span class="block text-gray-300">{{ $user->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Approve button (vendors only, when not active) --}}
                                    @if($user->role === 'vendor' && (!$user->vendor || !$user->vendor->active))
                                        <form method="POST"
                                              action="{{ route('admin.accounts.approve', $user->user_id) }}"
                                              onsubmit="return confirm('Approve vendor account for {{ addslashes($user->username) }}?')">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete button --}}
                                    <form method="POST"
                                          action="{{ route('admin.accounts.delete', $user->user_id) }}"
                                          onsubmit="return confirm('Permanently delete account \'{{ addslashes($user->username) }}\'? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-500 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No accounts found</p>
                                    <p class="text-xs text-gray-400 mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
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

    function showSpinner() {
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    }

    // Live search with 300ms debounce
    input.addEventListener('input', function() {
        clearTimeout(timer);
        showSpinner();
        timer = setTimeout(function() {
            form.submit();
        }, 300);
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
