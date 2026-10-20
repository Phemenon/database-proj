<x-layouts.app title="Wishlist – Fantasy Shop">
    <h1 class="mb-6 font-heading text-3xl text-primary">My Wishlist</h1>

    @if ($products->isEmpty())
        <x-empty-state title="Your wishlist is empty" message="Add products you like to your wishlist to view them here later."
                       action-label="Browse products" :href="route('products.index')" />
    @else
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedIds)" />
            @endforeach
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</x-layouts.app>