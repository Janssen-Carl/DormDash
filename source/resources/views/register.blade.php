<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DormDash - Sign Up</title>

    <!-- CDN for now, unless install tailwind with laravel -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Change in laravel integration -->
    <link rel="stylesheet" href="styles.css">

</head>

<body class="bg-gray-50">

<!-- Navbar -->
<nav class="flex justify-between bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">DormDash</h1>
    </div>
    <button
        class="mt-2 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700"
    >
        Sign Up
    </button>
</nav>

<!-- Main Content -->
<main class="flex min-h-screen">
    <!-- Left Side -->
    <div class="flex w-1/2 flex-col justify-center bg-gray-50 px-12">
        <h1 class="text-4xl font-bold text-gray-900">Register</h1>
        <p class="mt-4 text-gray-600">Enter your credentials to continue.</p>
    </div>

    <!-- Right Side -->
    <div class="flex w-1/2 flex-col justify-center bg-gray-50 px-12">
        <form class="space-y-6" method="post" action="/register">
            @csrf
            <!-- Email Input -->

            <div>
                <label for="username" class="block text-sm font-medium text-gray-900"
                >Username</label
                >
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="juandelacruz@email.com"
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-900"
                >Email</label
                >
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="juandelacruz@email.com"
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                />
                <p class="mt-2 text-xs text-gray-500">
                    We'll never share your email. Trust
                </p>
            </div>

            <!-- Password Input -->
            <div>
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-900"
                >Password</label
                >
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="password123"
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                />
                <p class="mt-2 text-xs text-gray-500">Minimum 8 characters</p>
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-900"
                >Confirm Password</label
                >
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                />
            </div>


            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <button
                    type="submit"
                    class="flex-1 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700"
                >
                    Register
                </button>
            </div>
        </form>
    </div>
</main>

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

