<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CDN for now, unless install tailwind with laravel -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Change in laravel integration -->
    <!--  <link rel="stylesheet" href="/resources/css/styles.css"> -->

    <title>Home</title>
</head>

<body>
    This is home page!!!

    <!-- Navbar -->
    <nav class="flex bg-gray-50 px-4 py-6 sm:px-6 lg:px-8">
       <!--  <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">DormDash</h1>
        </div> -->

        <button class="mt-2 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700">
            <a href="/register">
                Register
            </a>
        </button>

        <button class="mt-2 rounded-lg bg-green-600 px-6 py-2 font-medium text-white transition-colors hover:bg-green-700">
            <a href="/login">
                Login
            </a>
        </button>
    </nav>


</body>

</html>