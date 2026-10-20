<x-layouts.app title="Checkout – Fantasy Shop">
    <h1 class="mb-6 font-heading text-3xl text-primary">Checkout</h1>

    <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
        <form action="{{ route('checkout.store') }}" method="POST"
              onsubmit="this.querySelector('button[type=submit]').disabled = true"
              class="space-y-5 rounded-xl border border-line bg-white p-6">
            @csrf
            <h2 class="font-medium">Shipping Information</h2>

            <div class="text-sm">
                <p><span class="text-muted">Recipient:</span> {{ auth()->user()->name }}</p>
                <p><span class="text-muted">Phone:</span> {{ auth()->user()->phone }}</p>
            </div>

            <div>
                <label for="shipping_address" class="mb-1 block text-sm font-medium">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" required
                          class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none {{ $errors->has('shipping_address') ? 'border-red-400 bg-red-50' : 'border-line focus:border-primary' }}">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                @error('shipping_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-primary py-2 text-white hover:bg-primary-hover disabled:opacity-60">
                Confirm Order
            </button>
        </form>

        <aside class="h-fit rounded-xl border border-line bg-white p-5">
            <h2 class="mb-4 font-medium">Order Summary</h2>
            <ul class="space-y-3 text-sm">
                @foreach ($items as $product)
                    <li class="flex justify-between gap-3">
                        <span>{{ $product->name }} × {{ $product->pivot->quantity }}</span>
                        <span>฿{{ number_format($product->price * $product->pivot->quantity) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 flex justify-between border-t border-line pt-3 text-lg font-semibold">
                <span>Total</span>
                <span>฿{{ number_format($total) }}</span>
            </div>
            <a href="{{ route('cart.index') }}" class="mt-4 block text-center text-sm text-muted hover:text-primary">← Return to Cart</a>
        </aside>
    </div>
</x-layouts.app>