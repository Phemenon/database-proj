@props(['product', 'wishlisted' => false])
<article class="relative overflow-hidden rounded-xl border border-line bg-white">
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="relative aspect-square bg-stone-100">
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">
            @endif
            <x-rarity-badge :rarity="$product->rarity" class="absolute bottom-2 left-2" />
        </div>
    </a>

    @auth
        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute right-2 top-2">
            @csrf
            <button type="submit" aria-label="{{ $wishlisted ? 'Delete from Wishlist' : 'Add to Wishlist' }}"
                    class="flex size-8 items-center justify-center rounded-full bg-white/90 shadow hover:bg-white {{ $wishlisted ? 'text-red-500' : 'text-stone-500' }}">
                {{ $wishlisted ? '♥' : '♡' }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" aria-label="Add to Wishlist"
           class="absolute right-2 top-2 flex size-8 items-center justify-center rounded-full bg-white/90 text-stone-500 shadow hover:bg-white">♡</a>
    @endauth

    <div class="space-y-1 p-4">
        <p class="text-xs text-muted">{{ $product->category?->name }}</p>
        <a href="{{ route('products.show', $product) }}" class="block font-medium hover:text-primary">{{ $product->name }}</a>
        <p class="font-semibold">฿{{ number_format($product->price) }}</p>
        <x-star-rating :rating="$product->reviews_avg_rating" :count="$product->reviews_count" />
        @if ($product->stock > 0)
            <p class="text-xs text-green-700">In Stock ({{ $product->stock }})</p>
        @else
            <p class="text-xs text-red-600">Out of Stock</p>
        @endif

        <div class="pt-3">
            @if ($product->stock <= 0)
                <button type="button" disabled class="w-full rounded-lg bg-stone-200 py-2 text-sm text-stone-500">Out of Stock</button>
            @else
                @auth
                    <form action="{{ route('cart.items.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full rounded-lg bg-primary py-2 text-sm text-white hover:bg-primary-hover">Add to Cart</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full rounded-lg bg-primary py-2 text-center text-sm text-white hover:bg-primary-hover">Add to Cart</a>
                @endauth
            @endif
        </div>
    </div>
</article>