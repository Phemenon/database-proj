<x-layouts.app title="Cart – Fantasy Shop">
    <h1 class="mb-6 font-heading text-3xl text-primary">Cart</h1>

    @if ($items->isEmpty())
        <x-empty-state title="Your cart is empty" message="Start adding items you like!"
                       action-label="Go shopping" :href="route('products.index')" />
    @else
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div class="space-y-4">
                @foreach ($items as $product)
                    <article class="flex gap-4 rounded-xl border border-line bg-white p-4">
                        <a href="{{ route('products.show', $product) }}"
                           class="size-24 shrink-0 overflow-hidden rounded-lg bg-stone-100">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">
                            @endif
                        </a>

                        <div class="flex flex-1 flex-col gap-2">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <a href="{{ route('products.show', $product) }}" class="font-medium hover:text-primary">{{ $product->name }}</a>
                                    <p class="text-sm text-muted">฿{{ number_format($product->price) }} / item</p>
                                </div>
                                <p class="font-semibold">฿{{ number_format($product->price * $product->pivot->quantity) }}</p>
                            </div>

                            @if ($product->pivot->quantity > $product->stock)
                                <p class="text-sm text-amber-700">⚠ Only {{ $product->stock }} items left. Please reduce the quantity.</p>
                            @endif

                            <div class="mt-auto flex flex-wrap items-center gap-4">
                                <form action="{{ route('cart.items.update', $product) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <x-quantity-input :value="$product->pivot->quantity" :max="$product->stock" />
                                    <button type="submit" class="rounded-lg border border-line px-3 py-2 text-sm hover:bg-cream">Update</button>
                                </form>

                                <form action="{{ route('cart.items.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="h-fit rounded-xl border border-line bg-white p-5">
                <h2 class="mb-4 font-medium">Order Summary</h2>
                <div class="flex justify-between text-sm">
                    <span>Number of Items</span>
                    <span>{{ $items->sum('pivot.quantity') }} items</span>
                </div>
                <div class="mt-3 flex justify-between border-t border-line pt-3 text-lg font-semibold">
                    <span>Total</span>
                    <span>฿{{ number_format($total) }}</span>
                </div>

                @if ($hasStockIssue)
                    <button type="button" disabled class="mt-5 w-full rounded-lg bg-stone-300 py-2 text-stone-500">Please update quantities before checking out</button>
                @else
                    <a href="#" class="mt-5 block w-full rounded-lg bg-primary py-2 text-center text-white hover:bg-primary-hover">Go to Checkout</a>
                @endif
            </aside>
        </div>
    @endif
</x-layouts.app>