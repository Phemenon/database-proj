<x-layouts.app title="Products – Fantasy Shop">
    <div class="grid gap-8 lg:grid-cols-[16rem_1fr]">
        <aside>
            <form id="filters" action="{{ route('products.index') }}" method="GET"
                  class="space-y-6 rounded-xl border border-line bg-white p-5">
                <input type="hidden" name="q" value="{{ request('q') }}">

                <fieldset>
                    <legend class="mb-2 font-medium">Category</legend>
                    @foreach ($categories as $category)
                        <label class="flex items-center gap-2 py-1 text-sm">
                            <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                   @checked(in_array($category->id, (array) request('category')))>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </fieldset>

                <fieldset>
                    <legend class="mb-2 font-medium">Rarity</legend>
                    <label class="flex items-center gap-2 py-1 text-sm">
                        <input type="radio" name="rarity" value="" @checked(! request('rarity'))> All
                    </label>
                    @foreach ($rarities as $rarity)
                        <label class="flex items-center gap-2 py-1 text-sm">
                            <input type="radio" name="rarity" value="{{ $rarity }}"
                                   @checked(request('rarity') === $rarity)>
                            {{ ucfirst($rarity) }}
                        </label>
                    @endforeach
                </fieldset>

                <fieldset>
                    <legend class="mb-2 font-medium">Price Range</legend>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" min="0" placeholder="Min" value="{{ request('min_price') }}"
                               class="w-full rounded-lg border border-line px-2 py-1 text-sm">
                        <span class="text-muted">–</span>
                        <input type="number" name="max_price" min="0" placeholder="Max" value="{{ request('max_price') }}"
                               class="w-full rounded-lg border border-line px-2 py-1 text-sm">
                    </div>
                </fieldset>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-primary py-2 text-sm text-white hover:bg-primary-hover">Apply</button>
                    <a href="{{ route('products.index') }}" class="rounded-lg border border-line px-3 py-2 text-sm hover:bg-cream">Clear</a>
                </div>
            </form>
        </aside>

        <section>
            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-muted">
                    @if (request('q')) Search results for “{{ request('q') }}” · @endif
                    {{ $products->total() }} items
                </p>

                <select name="sort" form="filters" onchange="this.form.submit()"
                        class="rounded-lg border border-line bg-white px-3 py-2 text-sm">
                    @foreach (['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'rating' => 'Top Rated'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('sort', 'newest') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="col-span-full py-12 text-center text-muted">No products found.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </section>
    </div>
</x-layouts.app>