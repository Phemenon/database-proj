<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // แสดงประวัติคำสั่งซื้อทั้งหมดของ user
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // หน้ายืนยันการสั่งซื้อ (Checkout Form)
    public function create()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = $cart->products;
        $totalPrice = $products->sum(fn($p) => $p->price * $p->pivot->quantity);

        return view('orders.checkout', compact('products', 'totalPrice', 'user'));
    }

    // บันทึกคำสั่งซื้อ + ตัดสต็อก + ล้างตะกร้า (ใช้ DB::transaction)
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($user, $cart, $request) {
                $totalAmount = 0;

                // ตรวจสอบสต็อกล่าสุดก่อนเริ่มบันทึก
                foreach ($cart->products as $product) {
                    if ($product->stock < $product->pivot->quantity) {
                        throw new \Exception("Product '{$product->name}' is out of stock or insufficient.");
                    }
                    $totalAmount += $product->price * $product->pivot->quantity;
                }

                // 1. สร้างหัวบิลในตาราง orders (ใช้ฟิลด์ total ตาม schema)
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'total' => $totalAmount,
                    'shipping_address' => $request->shipping_address,
                ]);

                // 2. สร้างรายการใน order_items โดยกำหนด item_no รันตามลำดับ 1, 2, 3...
                $itemNo = 1;
                foreach ($cart->products as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_no' => $itemNo++,
                        'product_id' => $product->id,
                        'quantity' => $product->pivot->quantity,
                        'unit_price' => $product->price,
                    ]);

                    // ตัดสต็อกสินค้าในตาราง products
                    $product->decrement('stock', $product->pivot->quantity);
                }

                // 3. ล้างตะกร้าสินค้า
                $cart->products()->detach();

                return $order;
            });

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    // แสดงรายละเอียดคำสั่งซื้อ
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}