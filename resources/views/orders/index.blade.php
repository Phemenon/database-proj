<x-layouts.app title="Orders – Fantasy Shop">
    <h1 class="mb-6 font-heading text-3xl text-primary">Orders</h1>

    @if ($orders->isEmpty())
        <x-empty-state title="No Orders Found" message="When you place an order, it will appear here."
                       action-label="Start Shopping" :href="route('products.index')" />
    @else
        <div class="overflow-x-auto rounded-xl border border-line bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-line text-muted">
                    <tr>
                        <th class="px-4 py-3 font-medium">No</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Total</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b border-line last:border-0 hover:bg-cream">
                            <td class="px-4 py-3">
                                <a href="{{ route('orders.show', $order) }}" class="font-medium text-primary hover:underline">
                                    #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">฿{{ number_format($order->total) }}</td>
                            <td class="px-4 py-3"><x-order-status :status="$order->status" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</x-layouts.app>