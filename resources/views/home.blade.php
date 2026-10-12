<h1>Fantasy Shop</h1>
<p>ไอเทมแฟนตาซีจากทุกมุมโลก</p>

@auth
    <a href="{{ route('profile') }}">{{ Auth::user()->name }}</a>
@else
    <a href="{{ route('login') }}">Login</a> | <a href="{{ route('register') }}">Register</a>
@endauth
| <a href="{{ route('products.index') }}">All products</a>

<h2>Categories</h2>
@foreach ($categories as $c)
    <a href="{{ route('products.index', ['category' => $c->id]) }}">{{ $c->name }}</a>
@endforeach

<h2>Featured</h2>
@forelse ($featured as $p)
    <div>
        <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
        | {{ $p->rarity }} | ฿{{ number_format($p->price, 2) }}
    </div>
@empty
    <p>ยังไม่มีสินค้าเด่น</p>
@endforelse

<h2>New arrivals</h2>
@forelse ($latest as $p)
    <div>
        <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
        | {{ $p->category->name }} | ฿{{ number_format($p->price, 2) }}
        | ★ {{ $p->reviews_avg_rating ? number_format($p->reviews_avg_rating, 1) : 'ยังไม่มีรีวิว' }}
    </div>
@empty
    <p>ยังไม่มีสินค้า</p>
@endforelse