<h1>{{ $product->name }}</h1>
<p>{{ $product->category->name }} | {{ $product->rarity }} | ฿{{ number_format($product->price, 2) }} | stock: {{ $product->stock }}</p>
<p>{{ $product->description }}</p>

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
<h2>
    Reviews ({{ $product->reviews->count() }})
    @if ($average) — ★ {{ number_format($average, 1) }} / 5 @endif
</h2>

@auth
    @if ($errors->any())
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif

    <form method="POST" action="{{ route('reviews.store', $product) }}">
        @csrf
        <select name="rating">
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(old('rating', $myReview?->rating) == $i)>{{ $i }} ★</option>
            @endfor
        </select>
        <textarea name="comment" placeholder="เขียนรีวิว">{{ old('comment', $myReview?->comment) }}</textarea>
        <button>{{ $myReview ? 'แก้ไขรีวิว' : 'ส่งรีวิว' }}</button>
    </form>

    @if ($myReview)
        <form method="POST" action="{{ route('reviews.destroy', $product) }}">
            @csrf
            @method('DELETE')
            <button>ลบรีวิวของฉัน</button>
        </form>
    @endif
@else
    <a href="{{ route('login') }}">Login เพื่อรีวิว</a>
@endauth

@forelse ($product->reviews as $r)
    <p>{{ $r->user->name }}: {{ $r->rating }}/5 — {{ $r->comment }}</p>
@empty
    <p>ยังไม่มีรีวิว</p>
@endforelse