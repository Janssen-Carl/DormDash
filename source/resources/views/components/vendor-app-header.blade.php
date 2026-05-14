<header class="flex h-15 items-center justify-between border-b border-gray-200 bg-white px-8">
    {{-- Logo --}}
    <a href="/vendor-home" class="flex items-center gap-2.5 transition-opacity hover:opacity-80">
        <div class="flex h-8.5 w-8.5 items-center justify-center rounded-full bg-green-600">
            <x-heroicon-o-home class="h-4 w-4 text-white" />
        </div>
        <span class="text-lg font-bold tracking-tight text-gray-900">DormDash</span>
    </a>

    {{-- Nav Links --}}
    <nav class="flex items-center gap-1">
        <a
            href="/vendor-home"
            class="{{ request()->routeIs('vendor-home') || request()->path() === 'vendor-home' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm font-medium transition-colors"
        >
            <x-heroicon-o-home class="h-3.5 w-3.5" />
            Home
        </a>

        <a
            href="/vendor-products"
            class="{{ request()->routeIs('vendor-products') || request()->path() === 'vendor-products' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <x-heroicon-o-squares-2x2 class="h-3.5 w-3.5" />
            Products
        </a>

        <div class="mx-1.5 h-5 w-px bg-gray-200"></div>

            {{-- Profile Dropdown --}}
            <div class="relative group">
                <button
                    class="{{ request()->path() === 'profile' ? 'border-2 border-green-300' : 'border-2 border-green-200' }} flex h-8.5 w-8.5 cursor-pointer items-center justify-center rounded-full bg-green-600 text-xs font-semibold text-white transition-all hover:border-green-300"
                >
                    @if (auth()->user())
                        {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 2)) }}
                    @else
                        <x-heroicon-o-user class="h-5 w-5 text-white" />
                    @endif
                </button>
                
                {{-- Dropdown Menu --}}
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="/vendor-profile" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-lg">
                        <x-heroicon-o-user class="inline h-4 w-4 mr-2" />
                        My Profile
                    </a>
                    
                    <form method="POST" action="/logout" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-gray-50 last:rounded-b-lg border-t border-gray-200">
                            <x-heroicon-o-arrow-right-start-on-rectangle class="inline h-4 w-4 mr-2" />
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
    </nav>
</header>
