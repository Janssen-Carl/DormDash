<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>Home</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="flex justify-between bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">DormDash</h1>
        </div>
        <a  href="/register" class="mt-2 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700">
            <button>
                Sign Up
            </button>
        </a>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-[#04244E] relative w-full">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-white">
                2024 DormDash. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
