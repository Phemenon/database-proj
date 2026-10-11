<h1>Products</h1>

@foreach ($categories as $c)
    <a href="{{ route('products.index', ['category' => $c->id]) }}">{{ $c->name }}</a>
@endforeach

@foreach ($products as $p)
    <div>
        <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
        | {{ $p->category->name }} | {{ $p->rarity }}
        | ฿{{ number_format($p->price, 2) }} | stock: {{ $p->stock }}
        | ★ {{ $p->reviews_avg_rating ? number_format($p->reviews_avg_rating, 1) : '-' }}
    </div>
@endforeach

{{ $products->links() }}