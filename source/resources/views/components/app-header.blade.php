<header class="flex h-15 items-center justify-between border-b border-gray-200 bg-white px-8">
    {{-- Logo --}}
    <a href="/home" class="flex items-center gap-2.5 transition-opacity hover:opacity-80">
        <img src="{{ asset('images/logo.png') }}" alt="DormDash Logo" class="h-8 w-8 object-contain rounded-md" />
        <span class="text-lg font-bold tracking-tight text-gray-900">DormDash</span>
    </a>

    {{-- Nav Links --}}
    <nav class="flex items-center gap-1">
        @auth
        <a
            href="/home"
            class="{{ request()->routeIs('home') || request()->path() === 'home' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm font-medium transition-colors"
        >
            <x-heroicon-o-home class="h-3.5 w-3.5" />
            Home
        </a>

        <a
            href="/products"
            class="{{ request()->path() === 'products' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <x-heroicon-o-squares-2x2 class="h-3.5 w-3.5" />
            Products
        </a>

        <a
            href="/cart"
            class="{{ request()->path() === 'cart' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} relative flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            @php
                $cartCount = auth()->check() ? \App\Models\Cart::where('customer_id', auth()->id())->sum('quantity') : 0;
            @endphp
            <span class="relative">
                <x-heroicon-o-shopping-cart class="h-3.5 w-3.5" />
                @if($cartCount > 0)
                <span
                    class="absolute -top-1.5 -right-2 rounded-full bg-green-600 px-1 text-[9px] font-semibold text-white"
                >
                    {{ $cartCount }}
                </span>
                @endif
            </span>
            Cart
        </a>

        <!-- <a
            href="/orders-overview"
            class="{{ request()->path() === 'orders-overview' ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <x-heroicon-o-clipboard-document-list class="h-3.5 w-3.5" />
            Orders
        </a> -->

        <div class="mx-1.5 h-5 w-px bg-gray-200"></div>
        @endauth

        @auth
            {{-- Profile Dropdown --}}
            <div class="relative group">
                <button
                    class="{{ request()->path() === 'profile' ? 'border-2 border-green-300' : 'border-2 border-green-200' }} flex h-8.5 w-8.5 cursor-pointer items-center justify-center rounded-full bg-green-600 text-white transition-all hover:border-green-300"
                >
                    <x-heroicon-o-user class="h-5 w-5" />
                </button>
                
                {{-- Dropdown Menu --}}
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="/profile" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-lg">
                        <x-heroicon-o-user class="inline h-4 w-4 mr-2" />
                        My Profile
                    </a>
                    <a href="/orders-overview" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <x-heroicon-o-clipboard-document-list class="inline h-4 w-4 mr-2" />
                        Orders
                    </a>
                    <form method="POST" action="/logout" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-500 hover:text-white transition-colors duration-150 last:rounded-b-lg border-t border-gray-200">
                            <x-heroicon-o-arrow-right-start-on-rectangle class="inline h-4 w-4 mr-2" />
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- Guest Auth Links --}}
            <a href="/login" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 transition-colors">
                Log In
            </a>
            <a href="/register" class="px-4 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Sign Up
            </a>
        @endauth
    </nav>
</header>
