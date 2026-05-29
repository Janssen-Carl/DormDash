<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.isAuthenticated = @json(auth()->check());</script>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <title>@yield('title', 'DormDash')</title>

        <!-- @fluxAppearance -->
    </head>
    <body>
        @include('components.app-header')

        <main class="min-h-[calc(100vh-80px)]">
            @yield('content')
        </main>

        @include('components.footer')

        @livewireScripts
        @fluxScripts

        <script>
function showFloatingToast(message) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'flex items-center gap-3 rounded-2xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-2xl border border-green-500 transition-all duration-300 transform -translate-y-4 opacity-0 pointer-events-auto';
    toast.innerHTML = '<svg class="h-5 w-5 text-green-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg><span>' + message + '</span>';
    container.appendChild(toast);
    requestAnimationFrame(() => {
        toast.classList.remove('-translate-y-4', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    });
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-4', 'opacity-0');
        setTimeout(() => { toast.remove(); }, 300);
    }, 4000);
}

window.addToCart = function(event, form) {
    event.preventDefault();
    if (!window.isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) {
        if (r.ok) {
            var badge = document.getElementById('header-cart-badge');
            if (badge) {
                var qtyInput = form.querySelector('[name=quantity]');
                var qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                badge.textContent = (parseInt(badge.textContent) || 0) + qty;
                badge.style.display = 'inline-block';
            }
            showFloatingToast('Added to cart successfully.');
        } else {
            r.text().then(function(t) {
                try { var d = JSON.parse(t); alert(d.error || 'Error'); } catch(e) { form.submit(); }
            });
        }
    })
    .catch(function() { alert('Network error. Please try again.'); });
};

document.addEventListener('submit', function(e) {
    if (!window.isAuthenticated) {
        var form = e.target.closest('form');
        if (form && form.action && form.action.indexOf('/checkout') !== -1 && form.method.toLowerCase() === 'get') {
            e.preventDefault();
            window.location.href = '/login';
        }
    }
});

        </script>

    <button id="scrollTopBtn" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" type="button" aria-label="Scroll to Top"
            style="position:fixed;bottom:24px;right:24px;z-index:9999;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;background:linear-gradient(135deg,#059669,#047857);color:#fff;border:none;box-shadow:0 10px 25px -5px rgba(5,150,105,0.4),0 8px 10px -6px rgba(5,150,105,0.3);opacity:0;visibility:hidden;transform:scale(0.8);transition:all 0.35s cubic-bezier(0.34,1.56,0.64,1)">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:20px;height:20px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
        </svg>
    </button>

    <script>
    (function(){
        var btn = document.getElementById('scrollTopBtn');
        if (!btn) return;
        function toggle() {
            if (window.scrollY > 300) {
                btn.style.opacity = '1';
                btn.style.visibility = 'visible';
                btn.style.transform = 'scale(1)';
            } else {
                btn.style.opacity = '0';
                btn.style.visibility = 'hidden';
                btn.style.transform = 'scale(0.8)';
            }
        }
        window.addEventListener('scroll', toggle, { passive: true });
        toggle();
    })();
    </script>

    </body>
</html>
