<h1>{{ $product->name }}</h1>
<p>{{ $product->category->name }} | {{ $product->rarity }} | ฿{{ number_format($product->price, 2) }} | stock: {{ $product->stock }}</p>
<p>{{ $product->description }}</p>

<h2>Reviews ({{ $product->reviews->count() }})</h2>
@foreach ($product->reviews as $r)
    <p>{{ $r->user->name }}: {{ $r->rating }}/5 - {{ $r->comment }}</p>
@endforeach