<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-6">Transaction Details</h1>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Order ID</label>
                <p class="mt-1 text-gray-900">{{ $transaction->order_id }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Product</label>
                <p class="mt-1 text-gray-900">{{ $transaction->product->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <p class="mt-1 text-gray-900">{{ $transaction->quantity }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Total Price</label>
                <p class="mt-1 text-gray-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1 text-gray-900">{{ $transaction->status }}</p>
            </div>
            <a href="{{ url('/') }}" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 text-center block">Back to Home</a>
        </div>
    </div>
</body>
</html>
