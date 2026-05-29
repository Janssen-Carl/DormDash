@extends('layouts.vendor-main')

@section('title', 'Manage Discounts - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10">

    {{-- Header block --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900">
                Discounts & Campaigns
            </h1>
            <p class="mt-2 text-sm text-zinc-500">
                Create, monitor, and configure active promotions, seasonal campaigns, and item markdowns.
            </p>
        </div>
        <a href="{{ route('vendor.discounts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-emerald-600/10 transition-all hover:bg-emerald-700 hover:shadow-emerald-600/20 active:scale-[0.98]">
            <x-heroicon-o-plus class="h-5 w-5" />
            Create Promotion
        </a>
    </div>

    {{-- Success alert --}}
    @if(session('success'))
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-2xl border border-green-500" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
            <x-heroicon-s-check-circle class="h-5 w-5 text-green-200" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Error alert --}}
    @if($errors->any())
        <div class="mb-8 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 shadow-sm flex items-center gap-3">
            <x-heroicon-s-x-circle class="h-5 w-5 text-red-600 shrink-0" />
            <span class="font-semibold">{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Alpine Filter Engine Wrapper --}}
    @php
        $now = now();
        $activeCount = $discounts->filter(fn($d) => $d->is_active && $now->between($d->date_start, $d->date_end))->count();
        $scheduledCount = $discounts->filter(fn($d) => $d->is_active && $now->lt($d->date_start))->count();
        $inactiveCount = $discounts->filter(fn($d) => !$d->is_active || $now->gt($d->date_end))->count();
    @endphp
    <div x-data="{
        searchQuery: '',
        statusFilter: '', // 'active', 'scheduled', 'inactive_ended', or '' (all)
        campaignsList: [
            @foreach($discounts as $discount)
                @php
                    $start = \Carbon\Carbon::parse($discount->date_start);
                    $end = \Carbon\Carbon::parse($discount->date_end);
                    if (!$discount->is_active) {
                        $itemStatus = 'Inactive';
                    } elseif ($now->lt($start)) {
                        $itemStatus = 'Scheduled';
                    } elseif ($now->gt($end)) {
                        $itemStatus = 'Expired';
                    } else {
                        $itemStatus = 'Active';
                    }
                @endphp
                {
                    id: '{{ $discount->discount_id }}',
                    name: '{{ addslashes($discount->name) }}',
                    productName: '{{ addslashes($discount->item->name) }}',
                    sku: '{{ addslashes($discount->item->sku ?? '') }}',
                    description: '{{ addslashes($discount->description ?? '') }}',
                    status: '{{ $itemStatus }}'
                },
            @endforeach
        ],
        toggleFilter(filter) {
            if (this.statusFilter === filter) {
                this.statusFilter = '';
            } else {
                this.statusFilter = filter;
            }
        },
        matchesFilter(status) {
            if (!this.statusFilter) return true;
            if (this.statusFilter === 'active') return status === 'Active';
            if (this.statusFilter === 'scheduled') return status === 'Scheduled';
            if (this.statusFilter === 'inactive_ended') return status === 'Inactive' || status === 'Expired';
            return true;
        },
        matchesSearch(name, productName, sku, description) {
            if (!this.searchQuery) return true;
            let query = this.searchQuery.toLowerCase();
            return (name || '').toLowerCase().includes(query) ||
                   (productName || '').toLowerCase().includes(query) ||
                   (sku || '').toLowerCase().includes(query) ||
                   (description || '').toLowerCase().includes(query);
        },
        get filteredCount() {
            return this.campaignsList.filter(c => this.matchesFilter(c.status) && this.matchesSearch(c.name, c.productName, c.sku, c.description)).length;
        }
    }">

        {{-- KPI Cards (Interactive Filters) --}}
        <div class="grid gap-6 sm:grid-cols-3 mb-10">
            {{-- Card 1: Active --}}
            <div 
                @click="toggleFilter('active')"
                class="relative overflow-hidden rounded-3xl border p-6 cursor-pointer select-none transition-all duration-300 hover:scale-[1.01] hover:shadow-md"
                :class="statusFilter === 'active' ? 'ring-2 ring-emerald-500 bg-emerald-50/40 border-emerald-400 shadow-emerald-100' : 'border-emerald-100 bg-emerald-50/20 shadow-sm'"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active Campaigns</p>
                        <h3 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ $activeCount }}</h3>
                    </div>
                    <div class="rounded-2xl bg-emerald-500/10 p-3 text-emerald-600">
                        <x-heroicon-o-sparkles class="h-6 w-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-emerald-600">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Live and visible to customers </span>
                </div>
            </div>

            {{-- Card 2: Scheduled --}}
            <div 
                @click="toggleFilter('scheduled')"
                class="relative overflow-hidden rounded-3xl border p-6 cursor-pointer select-none transition-all duration-300 hover:scale-[1.01] hover:shadow-md"
                :class="statusFilter === 'scheduled' ? 'ring-2 ring-blue-500 bg-blue-50/40 border-blue-400 shadow-blue-100' : 'border-blue-100 bg-blue-50/20 shadow-sm'"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Scheduled Campaigns</p>
                        <h3 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ $scheduledCount }}</h3>
                    </div>
                    <div class="rounded-2xl bg-blue-500/10 p-3 text-blue-600">
                        <x-heroicon-o-calendar class="h-6 w-6" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-blue-500 flex items-center gap-1">
                    <x-heroicon-o-clock class="h-4 w-4" />
                    <span>Auto-launches at date start </span>
                </div>
            </div>

            {{-- Card 3: Ended or Inactive --}}
            <div 
                @click="toggleFilter('inactive_ended')"
                class="relative overflow-hidden rounded-3xl border p-6 cursor-pointer select-none transition-all duration-300 hover:scale-[1.01] hover:shadow-md"
                :class="statusFilter === 'inactive_ended' ? 'ring-2 ring-zinc-500 bg-zinc-100/50 border-zinc-400 shadow-zinc-100' : 'border-zinc-200 bg-zinc-50/20 shadow-sm'"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-zinc-600">Ended or Inactive</p>
                        <h3 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ $inactiveCount }}</h3>
                    </div>
                    <div class="rounded-2xl bg-zinc-200 p-3 text-zinc-500">
                        <x-heroicon-o-archive-box class="h-6 w-6" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-zinc-500 flex items-center gap-1">
                    <x-heroicon-o-information-circle class="h-4 w-4" />
                    <span>Expired or paused runs </span>
                </div>
            </div>
        </div>

        {{-- Search & Active Filter Info Row --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            {{-- Search input --}}
            <div class="relative max-w-md w-full">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                </span>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Search campaigns, products, or SKU..."
                    class="w-full rounded-2xl border border-zinc-200 bg-white pl-11 pr-10 py-3 text-sm text-zinc-800 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder-zinc-400 font-semibold shadow-sm"
                >
                {{-- Clear button --}}
                <button
                    x-show="searchQuery.length > 0"
                    @click="searchQuery = ''"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600"
                    style="display: none;"
                >
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            {{-- Active Filter Pills --}}
            <div class="flex items-center gap-3">
                <template x-if="statusFilter || searchQuery">
                    <button
                        @click="statusFilter = ''; searchQuery = '';"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-zinc-100 border border-zinc-200 px-4 py-2.5 text-xs font-bold text-zinc-600 hover:bg-zinc-200 hover:text-zinc-900 transition shadow-sm"
                    >
                        <x-heroicon-o-x-circle class="h-4 w-4 text-zinc-500" />
                        Reset Filters
                    </button>
                </template>
                <div class="text-xs font-semibold text-zinc-400" x-show="!statusFilter && !searchQuery">
                    Click a card above to filter by status
                </div>
                <div class="text-xs font-bold text-zinc-600 flex items-center gap-1.5" x-show="statusFilter" style="display: none;">
                    <span>Active Filter:</span>
                    <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">
                        <span x-text="statusFilter === 'active' ? 'Active Only' : (statusFilter === 'scheduled' ? 'Scheduled Only' : 'Inactive / Expired')"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full max-w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50 text-xs font-bold text-zinc-500 uppercase tracking-wider">
                            <th class="px-4 py-3.5 whitespace-nowrap">Product Item</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Campaign details</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Promotional Rate</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Original vs Promo Price</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Validity Duration</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Redemptions</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Status</th>
                            <th class="px-4 py-3.5 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-sm text-zinc-700 bg-white">
                        @forelse($discounts as $discount)
                            @php
                                $start = \Carbon\Carbon::parse($discount->date_start);
                                $end = \Carbon\Carbon::parse($discount->date_end);
                                
                                if (!$discount->is_active) {
                                    $status = 'Inactive';
                                    $statusClass = 'bg-zinc-100 text-zinc-700 border-zinc-200';
                                    $statusDot = 'bg-zinc-400';
                                } elseif ($now->lt($start)) {
                                    $status = 'Scheduled';
                                    $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                    $statusDot = 'bg-blue-500';
                                } elseif ($now->gt($end)) {
                                    $status = 'Expired';
                                    $statusClass = 'bg-red-50 text-red-700 border-red-200';
                                    $statusDot = 'bg-red-500';
                                } else {
                                    $status = 'Active';
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    $statusDot = 'bg-emerald-500';
                                }
                            @endphp
                            <tr 
                                x-show="matchesFilter('{{ $status }}') && matchesSearch('{{ addslashes($discount->name) }}', '{{ addslashes($discount->item->name) }}', '{{ addslashes($discount->item->sku ?? '') }}', '{{ addslashes($discount->description ?? '') }}')"
                                x-transition
                                class="hover:bg-zinc-50/50 transition-colors"
                            >
                                {{-- Product image & name --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-14 w-14 shrink-0 rounded-2xl overflow-hidden bg-zinc-50 border border-zinc-200 shadow-inner flex items-center justify-center">
                                            @if ($discount->item->images->first())
                                                <img src="{{ asset($discount->item->images->first()->image) }}" alt="{{ $discount->item->name }}" class="h-full w-full object-cover" />
                                            @else
                                                <div class="text-zinc-300">
                                                    <x-heroicon-o-photo class="h-6 w-6" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-[180px] sm:max-w-[220px]">
                                            <p class="font-bold text-zinc-900 break-words line-clamp-2 leading-snug" title="{{ $discount->item->name }}">{{ $discount->item->name }}</p>
                                            <p class="text-xs text-zinc-400 mt-1">SKU: {{ $discount->item->sku ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Campaign Name --}}
                                <td class="px-4 py-4">
                                    <div class="min-w-0 max-w-[160px] sm:max-w-[200px]">
                                        <div class="font-bold text-zinc-900 break-words line-clamp-2 leading-snug" title="{{ $discount->name }}">{{ $discount->name }}</div>
                                        @if($discount->description)
                                            <div class="text-xs text-zinc-400 mt-1 break-words line-clamp-2 leading-snug" title="{{ $discount->description }}">{{ $discount->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- Reduction Rate --}}
                                <td class="px-4 py-4 font-bold whitespace-nowrap">
                                    @if($discount->type === 'percentage')
                                        <span class="inline-flex items-center gap-1 rounded-xl bg-red-50 border border-red-100 px-3 py-1.5 text-xs text-red-700 font-extrabold">
                                            <x-heroicon-s-sparkles class="h-3.5 w-3.5" />
                                            {{ number_format($discount->value) }}% OFF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-xl bg-red-50 border border-red-100 px-3 py-1.5 text-xs text-red-700 font-extrabold">
                                            <x-heroicon-s-sparkles class="h-3.5 w-3.5" />
                                            ₱{{ number_format($discount->value, 2) }} OFF
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Original vs Promo Price --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-emerald-600">₱{{ number_format($discount->item->discounted_price, 2) }}</span>
                                        <span class="text-xs text-zinc-400 line-through">₱{{ number_format($discount->item->price, 2) }}</span>
                                    </div>
                                </td>
                                
                                {{-- Date Range --}}
                                <td class="px-4 py-4 text-xs text-zinc-500 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wide w-8">From</span>
                                            <span class="font-medium text-zinc-700">{{ $start->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wide w-8">To</span>
                                            <span class="font-medium text-zinc-700">{{ $end->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Redemptions --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if($discount->use_limit)
                                        <span class="font-semibold text-zinc-700">{{ $discount->redemption_count ?? 0 }}</span>
                                        <span class="text-zinc-400"> / {{ $discount->use_limit }}</span>
                                    @else
                                        <span class="text-zinc-400">Unlimited</span>
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        @if($status === 'Active')
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                            </span>
                                        @else
                                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                
                                {{-- Actions --}}
                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a href="{{ route('vendor.discounts.edit', $discount->discount_id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-zinc-50 border border-zinc-200 text-zinc-600 transition-all hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200" title="Edit Promotion">
                                            <x-heroicon-o-pencil-square class="h-4.5 w-4.5" />
                                        </a>
                                        
                                        <form action="{{ route('vendor.discounts.destroy', $discount->discount_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion? It will immediately restore the product to its standard price.')" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-zinc-50 border border-zinc-200 text-red-500 transition-all hover:bg-red-500 hover:text-white hover:border-red-600" title="Delete Promotion">
                                                <x-heroicon-o-trash class="h-4.5 w-4.5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-zinc-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-3xl bg-zinc-100 flex items-center justify-center text-zinc-400 mb-4 border border-zinc-200">
                                            <x-heroicon-o-sparkles class="h-8 w-8" />
                                        </div>
                                        <p class="text-base font-bold text-zinc-800">No Promotions Created Yet</p>
                                        <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">Boost your sales by offering limited-time percentage or fixed markdowns for your products!</p>
                                        <a href="{{ route('vendor.discounts.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                            Create Your First Discount
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Client-side No Matches fallback row --}}
                        <tr x-show="filteredCount === 0" style="display: none;">
                            <td colspan="7" class="px-6 py-16 text-center text-zinc-400 bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 rounded-3xl bg-zinc-100 flex items-center justify-center text-zinc-400 mb-4 border border-zinc-200">
                                        <x-heroicon-o-magnifying-glass class="h-8 w-8" />
                                    </div>
                                    <p class="text-base font-bold text-zinc-800">No Matching Promotions</p>
                                    <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">Try adjusting your search query or clicking a different status filter card above.</p>
                                    <button @click="searchQuery = ''; statusFilter = '';" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-zinc-100 border border-zinc-200 px-4 py-2.5 text-xs font-bold text-zinc-700 hover:bg-zinc-200 transition shadow-sm">
                                        Clear All Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
