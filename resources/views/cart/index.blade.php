<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8 bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Your Shopping Cart</h1>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">← Continue Shopping</a>
        </div>

        @if(session('error'))
            <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">{{ session('success') }}</div>
        @endif

        @if($products->isEmpty())
            <div class="py-12 text-center text-gray-500">
                <p class="text-lg">Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-indigo-700">Shop Now</a>
            </div>
        @else
            <table class="w-full text-left border-collapse mb-6">
                <thead>
                    <tr class="border-b text-gray-500 text-sm">
                        <th class="py-3">Product</th>
                        <th class="py-3">Price</th>
                        <th class="py-3 text-center">Quantity</th>
                        <th class="py-3 text-right">Subtotal</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($products as $product)
                        <tr>
                            <td class="py-4 flex items-center gap-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                @endif
                                <div>
                                    <a href="{{ route('products.show', $product) }}" class="font-medium hover:text-indigo-600">{{ $product->name }}</a>
                                    <span class="block text-xs text-gray-400">Available: {{ $product->stock }} items</span>
                                </div>
                            </td>
                            <td class="py-4">฿{{ number_format($product->price, 2) }}</td>
                            <td class="py-4 text-center">
                                <form action="{{ route('cart.update', $product) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="1" max="{{ $product->stock }}" class="border rounded w-16 px-2 py-1 text-center">
                                    <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 border px-2 py-1 rounded">Update</button>
                                </form>
                            </td>
                            <td class="py-4 text-right font-medium">฿{{ number_format($product->price * $product->pivot->quantity, 2) }}</td>
                            <td class="py-4 text-center">
                                <form action="{{ route('cart.destroy', $product) }}" method="POST" onsubmit="return confirm('Remove this item from your cart?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between items-center border-t pt-6">
                <div>
                    <span class="text-gray-500">Total:</span>
                    <span class="text-2xl font-bold text-indigo-600 ml-2">฿{{ number_format($totalPrice, 2) }}</span>
                </div>
                <a href="{{ route('checkout.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-black font-semibold px-6 py-3 rounded-lg shadow inline-block">
                    Proceed to Checkout →
                </a>
            </div>
        @endif
    </div>
</body>
</html>