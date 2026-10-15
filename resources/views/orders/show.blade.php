@php $code = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT); @endphp
<x-layouts.app :title="$code . ' – Fantasy Shop'">
    <a href="{{ route('orders.index') }}" class="text-sm text-muted hover:text-primary">← All Orders</a>

    <div class="mb-6 mt-3 flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-heading text-3xl text-primary">{{ $code }}</h1>
        <div class="flex items-center gap-3 text-sm text-muted">
            <span>{{ $order->created_at->format('d M Y') }}</span>
            <x-order-status :status="$order->status" />
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
        <div class="overflow-x-auto rounded-xl border border-line bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-line text-muted">
                    <tr>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">Quantity</th>
                        <th class="px-4 py-3 font-medium">Price/Item</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-b border-line last:border-0">
                            <td class="px-4 py-3">
                                <a href="{{ route('products.show', $item->product_id) }}" class="flex items-center gap-3 hover:text-primary">
                                    <span class="size-12 shrink-0 overflow-hidden rounded-lg bg-stone-100">
                                        @if ($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="size-full object-cover">
                                        @endif
                                    </span>
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">฿{{ number_format($item->unit_price) }}</td>
                            <td class="px-4 py-3 text-right">฿{{ number_format($item->unit_price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <aside class="h-fit space-y-4 rounded-xl border border-line bg-white p-5">
            <div>
                <h2 class="mb-1 font-medium">Shipping Address</h2>
                <p class="whitespace-pre-line text-sm text-muted">{{ $order->shipping_address }}</p>
            </div>
            <div class="flex justify-between border-t border-line pt-3 text-lg font-semibold">
                <span>Total</span>
                <span>฿{{ number_format($order->total) }}</span>
            </div>
            
            @if($order->status === 'pending')
                {{-- กรอบใหญ่สีเขียวอ่อน --}}
                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0;" class="mt-6 p-5 rounded-2xl shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900">Payment Required</h3>
                                <span style="background-color: #fef3c7; color: #92400e;" class="px-2 py-0.5 text-xs font-semibold rounded-full">
                                    Awaiting
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Please confirm your payment to process this order.</p>
                        </div>

                        {{-- ปุ่มสี่เหลี่ยมสีเขียวเข้ม ตัวหนังสือสีขาวชัดเจน --}}
                        <form action="{{ route('orders.pay', $order) }}" method="POST" class="shrink-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    style="background-color: #059669; color: #ffffff; border: none;"
                                    class="w-full sm:w-auto px-6 py-3 font-semibold text-sm rounded-lg shadow hover:opacity-90 active:scale-95 transition cursor-pointer">
                                Pay ฿{{ number_format($order->total, 2) }}
                            </button>
                        </form>

                    </div>
                </div>
            @endif
        </aside>
    </div>
</x-layouts.app>