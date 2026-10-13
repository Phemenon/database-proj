<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8 bg-gray-50 text-gray-800">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Checkout</h1>

        <div class="mb-6 border-b pb-4">
            <h2 class="text-lg font-semibold mb-2">Order Summary</h2>
            <ul class="divide-y">
                @foreach($products as $product)
                    <li class="py-2 flex justify-between">
                        <span>{{ $product->name }} x {{ $product->pivot->quantity }}</span>
                        <span class="font-medium">฿{{ number_format($product->price * $product->pivot->quantity, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="text-right text-xl font-bold mt-4 text-indigo-600">
                Total: ฿{{ number_format($totalPrice, 2) }}
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block font-medium mb-1">Shipping Address</label>
                <textarea name="shipping_address" rows="3" required class="w-full border rounded p-2" placeholder="Enter your full address">{{ old('shipping_address', $user->address ?? '') }}</textarea>
                @error('shipping_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:underline">← Back to Cart</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-black font-semibold px-6 py-2 rounded shadow">
                    Place Order (Confirm)
                </button>
            </div>
        </form>
    </div>
</body>
</html>