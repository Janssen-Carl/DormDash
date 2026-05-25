{{-- Admin Sidebar --}}
<aside class="w-60 bg-gray-900 flex flex-col flex-shrink-0 h-screen sticky top-0">

    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 py-5 border-b border-gray-800 hover:bg-gray-800 transition-colors">
        <img src="{{ asset('images/logo.png') }}" alt="DormDash Logo" class="h-8 w-8 object-contain rounded-lg" />
        <div>
            <span class="block text-white font-bold text-sm leading-tight">DormDash</span>
            <span class="block text-emerald-400 text-xs font-medium">Admin Panel</span>
        </div>
    </a>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 px-3 space-y-1">

        <p class="px-3 pt-2 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Overview</p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Insights --}}
        <a href="{{ route('admin.insights') }}"
           class="{{ request()->routeIs('admin.insights') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
            Insights
        </a>

        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</p>

        {{-- Account Management --}}
        <a href="{{ route('admin.accounts') }}"
           class="{{ request()->routeIs('admin.accounts') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Accounts
        </a>

        {{-- Activity Logs --}}
        <a href="{{ route('admin.logs') }}"
           class="{{ request()->routeIs('admin.logs') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            Activity Logs
        </a>

    </nav>

    {{-- Bottom section --}}
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-gray-800">
            <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white flex-shrink-0">
                <x-heroicon-o-user class="h-4 w-4" />
            </div>
            <div class="min-w-0">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->username }}</p>
                <p class="text-emerald-400 text-xs">Administrator</p>
            </div>
        </div>
    </div>

</aside>
