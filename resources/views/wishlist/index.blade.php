<h1>My Wish List</h1>

@if (session('status')) <p>{{ session('status') }}</p> @endif

@forelse ($products as $p)
    <div>
        <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
        | {{ $p->category->name }} | {{ $p->rarity }} | ฿{{ number_format($p->price, 2) }}
        | ★ {{ $p->reviews_avg_rating ? number_format($p->reviews_avg_rating, 1) : 'ยังไม่มีรีวิว' }}
        <form method="POST" action="{{ route('wishlist.destroy', $p) }}" style="display:inline">
            @csrf
            @method('DELETE')
            <button>Remove</button>
        </form>
    </div>
@empty
    <p>ยังไม่มีสินค้าในรายการโปรด</p>
@endforelse