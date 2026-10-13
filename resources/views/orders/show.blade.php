<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8 bg-gray-50 text-gray-800">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        @if(session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">{{ session('success') }}</div>
        @endif

        <h1 class="text-2xl font-bold mb-2">Order #{{ $order->id }}</h1>
        <p class="text-sm text-gray-500 mb-4">Status: <span class="uppercase font-semibold text-indigo-600">{{ $order->status }}</span></p>

        <div class="mb-4 border-t border-b py-3 text-sm">
            <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        </div>

        <h2 class="font-semibold mb-2">Purchased Items</h2>
        <table class="w-full text-left border-collapse mb-4">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-2">#</th>
                    <th class="py-2">Product</th>
                    <th class="py-2 text-center">Quantity</th>
                    <th class="py-2 text-right">Unit Price</th>
                    <th class="py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-2 text-gray-400">{{ $item->item_no }}</td>
                        <td class="py-2 font-medium">{{ $item->product->name }}</td>
                        <td class="py-2 text-center">{{ $item->quantity }}</td>
                        <td class="py-2 text-right">฿{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-2 text-right font-medium">฿{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-xl font-bold text-indigo-600 mb-6">
            Total: ฿{{ number_format($order->total, 2) }}
        </div>

        <div class="flex justify-between">
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">← Continue Shopping</a>
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:underline">View All Orders</a>
        </div>
    </div>
</body>
</html>