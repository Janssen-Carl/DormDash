<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    @extends('home')

    @section('content')
    <!-- Main Content -->
    <main class="flex min-h-screen">
        <!-- Left Side -->
        <div class="flex w-1/2 flex-col justify-center bg-gray-50 px-12">
            <h1 class="text-4xl font-bold text-gray-900">Login</h1>
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

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </main>
    @endsection

</body>
</html>
