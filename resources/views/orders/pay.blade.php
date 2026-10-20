@php $code = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT); @endphp
<x-layouts.app :title="'payment ' . $code . ' – Fantasy Shop'">
    <div class="mx-auto max-w-md rounded-2xl border border-line bg-white p-8 text-center">
        <h1 class="font-heading text-2xl text-primary">pay with PromptPay</h1>
        <p class="mt-1 text-sm text-muted">Purchase Order {{ $code }}</p>

        <p class="mt-6 text-sm text-muted">Amount to Pay</p>
        <p class="text-3xl font-semibold">฿{{ number_format($order->total) }}</p>

        <img src="{{ asset('images/promptpay.png') }}" alt="PromptPay QR"
             class="mx-auto mt-6 size-64 rounded-lg border border-line object-contain">
        <p class="mt-2 text-xs text-muted">Scan the QR code with your banking app to transfer money (simulated system, no actual deduction)</p>

        <form action="{{ route('orders.pay.confirm', $order) }}" method="POST" class="mt-8"
              onsubmit="this.querySelector('button[type=submit]').disabled = true">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-primary py-3 text-white hover:bg-primary-hover disabled:opacity-60">
                confirm payment
            </button>
        </form>

        <a href="{{ route('orders.show', $order) }}" class="mt-4 block text-sm text-muted hover:text-primary">← back to order page</a>
    </div>
</x-layouts.app>