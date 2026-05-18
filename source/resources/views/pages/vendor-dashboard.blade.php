<!DOCTYPE html>
<html>
<head>

    <title>DormDash AI</title>

    @vite(['resources/css/app.css'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-gray-100">

<div class="p-8">

    <h1 class="text-4xl font-bold mb-8">
        DormDash AI Analytics
    </h1>

    <div class="grid grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-gray-500">
                Predicted Revenue
            </h2>

            <p class="text-3xl font-bold text-green-600">
                ₱{{ number_format($forecast['predicted_revenue'] ?? 0, 2) }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-gray-500">
                Low Stock Alerts
            </h2>

            <p class="text-3xl font-bold text-red-500">
                {{ is_array($inventory) ? count($inventory) : 0 }}
            </p>
        </div>

    </div>

    <div class="bg-white mt-8 p-6 rounded-xl shadow">

        <canvas id="salesChart"></canvas>

    </div>

    <div class="bg-white mt-8 p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold mb-4">
            Inventory Alerts
        </h2>

        <table class="w-full">

            <thead>
            <tr class="text-left border-b">
                <th>Item</th>
                <th>Stock</th>
            </tr>
            </thead>

            <tbody>

            @foreach($inventory as $item)

                <tr class="border-b">

                    <td class="py-2">
                        {{ $item['name'] }}
                    </td>

                    <td class="py-2 text-red-500">
                        {{ $item['stock'] }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

<script>

    const historical = @json($forecast['historical']);

    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'line',

        data: {

            labels: historical.map(h => h.order_date),

            datasets: [{
                label: 'Revenue',
                data: historical.map(h => h.revenue),
                borderColor: '#2563eb',
                tension: 0.4
            }]
        }
    });

</script>

</body>
</html>
