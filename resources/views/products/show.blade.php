<h1>{{ $product->name }}</h1>
<p>{{ $product->category->name }} | {{ $product->rarity }} | ฿{{ number_format($product->price, 2) }} | stock: {{ $product->stock }}</p>
<p>{{ $product->description }}</p>

<h2>Reviews ({{ $product->reviews->count() }})</h2>
@foreach ($product->reviews as $r)
    <p>{{ $r->user->name }}: {{ $r->rating }}/5 - {{ $r->comment }}</p>
@endforeach
@if (session('status')) <p>{{ session('status') }}</p> @endif

@auth
    @if ($inWishlist)
        <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
            @csrf
            @method('DELETE')
            <button>♥ Remove from wish list</button>
        </form>
    @else
        <form method="POST" action="{{ route('wishlist.store', $product) }}">
            @csrf
            <button>♡ Add to wish list</button>
        </form>
    @endif
@else
    <a href="{{ route('login') }}">Login เพื่อเพิ่มลงรายการโปรด</a>
@endauth