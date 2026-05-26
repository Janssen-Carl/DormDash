<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])
        <meta name="csrf-token" content="{{ csrf_token() }}">

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

        </script>

    </body>
</html>
