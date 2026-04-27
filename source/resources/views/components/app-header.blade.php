<header class="flex h-15 items-center justify-between border-b border-gray-200 bg-white px-8">
    {{-- Logo --}}
    <div class="flex items-center gap-2.5">
        <div class="flex h-8.5 w-8.5 items-center justify-center rounded-full bg-green-600">
            <x-heroicon-o-home class="h-4 w-4 text-white" />
        </div>
        <span class="text-lg font-bold tracking-tight text-gray-900">DormDash</span>
    </div>

    {{-- Nav Links --}}
    <nav class="flex items-center gap-1">
        <a
            href="/home"
            class="{{ request()->routeIs('home') ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm font-medium transition-colors"
        >
            <x-heroicon-o-home class="h-3.5 w-3.5" />
            Home
        </a>

        <a
            href="/products"
            class="{{ request()->routeIs('products.*') ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <x-heroicon-o-squares-2x2 class="h-3.5 w-3.5" />
            Products
        </a>

        <a
            href="/cart"
            class="{{ request()->routeIs('cart.*') ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} relative flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <span class="relative">
                <x-heroicon-o-shopping-cart class="h-3.5 w-3.5" />
                <span
                    class="absolute -top-1.5 -right-2 rounded-full bg-green-600 px-1 text-[9px] font-semibold text-white"
                >
                    4
                </span>
            </span>
            Cart
        </a>

        <a
            href="/orders"
            class="{{ request()->routeIs('orders.*') ? 'border border-green-200 bg-green-50 text-green-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-sm transition-colors"
        >
            <x-heroicon-o-clipboard-document-list class="h-3.5 w-3.5" />
            Orders
        </a>

        <div class="mx-1.5 h-5 w-px bg-gray-200"></div>

        {{-- Profile Avatar --}}
        <div
            class="flex h-8.5 w-8.5 cursor-pointer items-center justify-center rounded-full border-2 border-green-200 bg-green-600 text-xs font-semibold text-white"
        >
            @if (auth()->check() && auth()->user()->name)
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            @else
                <x-heroicon-o-user class="h-5 w-5 text-white" />
            @endif
        </div>
    </nav>
</header>
