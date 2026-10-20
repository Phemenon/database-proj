<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $items = $request->user()->cart?->products ?? collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($items->contains(fn ($p) => $p->pivot->quantity > $p->stock)) {
            return redirect()->route('cart.index')->with('warning', 'Some items in your cart exceed available stock. Please adjust quantities before proceeding.');
        }

        return view('checkout.create', [
            'items' => $items,
            'total' => $items->sum(fn ($p) => $p->price * $p->pivot->quantity),
        ]);
    }

    // บันทึกคำสั่งซื้อ + ตัดสต็อก + ล้างตะกร้า (ใช้ DB::transaction)
    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
        ], [
            'shipping_address.required' => 'Please enter your shipping address.',
        ]);

        $user = $request->user();
        $cart = $user->cart;

        if (! $cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $orderId = DB::transaction(function () use ($user, $cart, $data) {
                $lines = DB::table('cart_items')
                    ->where('cart_id', $cart->id)
                    ->lockForUpdate()
                    ->get();

                if ($lines->isEmpty()) {
                    throw new \DomainException('Your cart is empty. Please add items to your cart before proceeding to checkout.');
                }

                $products = Product::whereIn('id', $lines->pluck('product_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // ตรวจสอบสต็อกล่าสุดก่อนเริ่มบันทึก
                foreach ($lines as $line) {
                    $product = $products[$line->product_id];

                    if ($product->stock < $line->quantity) {
                        throw new \DomainException("Product {$product->name} has only {$product->stock} units available. Please adjust the quantity in your cart before proceeding to checkout.");
                    }
                }

                $total = $lines->sum(fn ($l) => $products[$l->product_id]->price * $l->quantity);
                $now   = now();

                // 1. สร้างหัวบิลในตาราง orders 
                $orderId = DB::table('orders')->insertGetId([
                    'user_id'          => $user->id,
                    'status'           => 'pending',
                    'total'            => $total,
                    'shipping_address' => $data['shipping_address'],
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);

                // 2. สร้างรายการใน order_items 
                foreach ($lines as $i => $line) {
                    $product = $products[$line->product_id];

                    DB::table('order_items')->insert([
                        'order_id'   => $orderId,
                        'item_no'    => $i + 1,
                        'product_id' => $product->id,
                        'quantity'   => $line->quantity,
                        'unit_price' => $product->price,
                    ]);

                    // ตัดสต็อกสินค้าในตาราง products
                    $product->decrement('stock', $line->quantity);
                }
                
                // 3. ล้างตะกร้าของผู้ใช้
                DB::table('cart_items')->where('cart_id', $cart->id)->delete();

                return $orderId;
            });
        } catch (\DomainException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('orders.show', $orderId)->with('success', 'Order placed successfully. Thank you for your purchase!');
    }
}