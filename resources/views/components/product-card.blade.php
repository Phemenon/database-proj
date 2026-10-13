@props(['product'])
<article class="overflow-hidden rounded-xl border border-line bg-white">
    <a href="#" class="block">
        <div class="relative aspect-square bg-stone-100">
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">
            @endif
            <x-rarity-badge :rarity="$product->rarity" class="absolute bottom-2 left-2" />
        </div>
    </a>
    <div class="space-y-1 p-4">
        <p class="text-xs text-muted">{{ $product->category?->name }}</p>
        <a href="#" class="block font-medium hover:text-primary">{{ $product->name }}</a>
        <p class="font-semibold">฿{{ number_format($product->price) }}</p>
        <x-star-rating :rating="$product->reviews_avg_rating" :count="$product->reviews_count" />
        @if ($product->stock > 0)
            <p class="text-xs text-green-700">In Stock ({{ $product->stock }})</p>
        @else
            <p class="text-xs text-red-600">Out of Stock</p>
        @endif
    </div>
</article>