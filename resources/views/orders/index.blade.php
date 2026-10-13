<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8 bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Order History</h1>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">← Back to Shop</a>
        </div>

        @if($orders->isEmpty())
            <div class="py-12 text-center text-gray-500">
                <p class="text-lg">You have not placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">Shop Now</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="border rounded-lg p-4 hover:shadow-sm transition">
                        <div class="flex justify-between items-center border-b pb-2 mb-3">
                            <div>
                                <span class="font-bold text-lg">Order #{{ $order->id }}</span>
                                <span class="text-sm text-gray-400 ml-2">{{ $order->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xs rounded-full uppercase font-bold 
                                    @if($order->status === 'completed') bg-green-100 text-green-700 
                                    @elseif($order->status === 'shipped') bg-blue-100 text-blue-700 
                                    @elseif($order->status === 'paid') bg-yellow-100 text-yellow-700 
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 mb-3">
                            @foreach($order->items as $item)
                                <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>@if(!$loop->last), @endif
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-lg font-bold text-indigo-600">Total: ฿{{ number_format($order->total, 2) }}</span>
                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline font-medium text-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>