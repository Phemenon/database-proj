<h1>{{ $product->name }}</h1>
<p>{{ $product->category->name }} | {{ $product->rarity }} | ฿{{ number_format($product->price, 2) }} | stock: {{ $product->stock }}</p>
<p>{{ $product->description }}</p>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<h2>Reviews ({{ $product->reviews->count() }})</h2>
@foreach ($product->reviews as $r)
    <p>{{ $r->user->name }}: {{ $r->rating }}/5 - {{ $r->comment }}</p>
@endforeach

<hr>

@auth
    @if ($product->stock > 0)
        <form action="{{ route('cart.store', $product) }}" method="POST">
            @csrf
            <div class="flex items-center gap-2">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="border rounded px-3 py-2 w-20 text-center">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded font-medium">
                    Add to Cart
                </button>
            </div>
        </form>
    @else
        <p class="text-red-600">Out of Stock</p>
    @endif
@else
    <p><a href="{{ route('login') }}" class="text-blue-600 underline">Sign in</a> to purchase this item.</p>
@endauth