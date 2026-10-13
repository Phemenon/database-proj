<x-layouts.app title="Fantasy Shop – home">
    <section class="rounded-2xl bg-primary px-8 py-16 text-white">
        <h1 class="font-heading text-4xl md:text-5xl">Discover Magical Items</h1>
        <p class="mt-3 max-w-md text-white/80">Step into a world of fantasy, where every item has a story.</p>
        <a href="#" class="mt-6 inline-block rounded-lg bg-white px-5 py-2 font-medium text-primary hover:bg-cream">Shop Now</a>
    </section>

    <section class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
        @foreach ($categories as $category)
            <a href="#" class="rounded-xl border border-line bg-white p-4 text-center hover:border-primary">
                {{ $category->name }}
            </a>
        @endforeach
    </section>

    <section class="mt-12">
        <div class="mb-4 flex items-end justify-between">
            <h2 class="font-heading text-2xl text-primary">New Arrivals</h2>
            <a href="#" class="text-sm text-muted hover:text-primary">View All →</a>
        </div>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse ($newArrivals as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full text-muted">No Products Available</p>
            @endforelse
        </div>
    </section>
    
    <section class="mt-12">
        <div class="mb-4 flex items-end justify-between">
            <h2 class="font-heading text-2xl text-primary">Top Rated</h2>
            <a href="#" class="text-sm text-muted hover:text-primary">View All →</a>
        </div>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse ($topRated as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full text-muted">No Products Available</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>