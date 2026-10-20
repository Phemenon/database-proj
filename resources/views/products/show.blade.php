<x-layouts.app :title="$product->name . ' – Fantasy Shop'">
    <nav class="mb-6 text-sm text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a> /
        @if ($product->category)
            <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-primary">{{ $product->category->name }}</a> /
        @endif
        <span class="text-ink">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-10 md:grid-cols-2">
        <div class="aspect-square overflow-hidden rounded-xl border border-line bg-stone-100">
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">
            @endif
        </div>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="font-heading text-3xl text-primary">{{ $product->name }}</h1>
                <x-rarity-badge :rarity="$product->rarity" />
            </div>

            <x-star-rating :rating="$product->reviews_avg_rating" :count="$product->reviews_count" />
            <p class="text-3xl font-semibold">฿{{ number_format($product->price) }}</p>

            @if ($product->stock > 0)
                <p class="text-sm text-green-700">In Stock ({{ $product->stock }})</p>
            @else
                <p class="text-sm text-red-600">Out of Stock</p>
            @endif

            <p class="text-muted">{{ $product->description }}</p>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                @if ($product->stock <= 0)
                    <button type="button" disabled class="rounded-lg bg-stone-300 px-6 py-2 text-stone-500">Out of Stock</button>
                @else
                    @auth
                        <form action="{{ route('cart.items.store') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <x-quantity-input :max="$product->stock" />
                            <button type="submit" class="rounded-lg bg-primary px-6 py-2 text-white hover:bg-primary-hover">Add to Cart</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-primary px-6 py-2 text-white hover:bg-primary-hover">Log in to Purchase</a>
                    @endauth
                @endif

                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg border border-line px-4 py-2 hover:bg-cream">
                            {{ in_array($product->id, $wishlistedIds) ? '♥ Remove from Wishlist' : '♡ Add to Wishlist' }}
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <section class="mt-14">
        <h2 class="mb-4 font-heading text-2xl text-primary">Reviews ({{ $product->reviews_count }})</h2>

        @forelse ($reviews as $review)
            <article class="flex gap-3 border-b border-line py-4">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary text-white">
                    {{ mb_substr($review->user->name, 0, 1) }}
                </span>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ $review->user->name }}</span>
                        <span class="text-xs text-muted">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    <x-star-rating :rating="$review->rating" :show-number="false" />
                    <p class="mt-1 text-sm">{{ $review->comment }}</p>
                </div>
            </article>
        @empty
            <p class="py-6 text-muted">There are no reviews yet. Be the first to write a review for this product!</p>
        @endforelse

        <div class="mt-4">{{ $reviews->links() }}</div>

        @auth
            <form action="#" method="POST" class="mt-8 space-y-4 rounded-xl border border-line bg-white p-5">
                @csrf
                <h3 class="font-medium">{{ $userReview ? 'Edit Your Review' : 'Write a Review' }}</h3>

                <div class="flex gap-2">
                    @foreach (range(1, 5) as $n)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $n }}" required
                                   class="peer sr-only" @checked(old('rating', $userReview?->rating) == $n)>
                            <span class="block rounded-lg border border-line px-3 py-1 text-sm peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">{{ $n }} ★</span>
                        </label>
                    @endforeach
                </div>
                @error('rating') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <textarea name="comment" rows="3" placeholder="Tell us about your experience with this product..."
                          class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-primary focus:outline-none">{{ old('comment', $userReview?->comment) }}</textarea>
                @error('comment') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm text-white hover:bg-primary-hover">
                    {{ $userReview ? 'Save Changes' : 'Submit Review' }}
                </button>
            </form>
        @else
            <p class="mt-8 text-sm text-muted">Please log in to write a review.</p>
        @endauth
    </section>
</x-layouts.app>