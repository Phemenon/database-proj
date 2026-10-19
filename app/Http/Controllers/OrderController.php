<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);

        $items = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('order_items.order_id', $order->id)
            ->orderBy('order_items.item_no')
            ->select('order_items.*', 'products.name', 'products.image_url')
            ->get();

        return view('orders.show', compact('order', 'items'));
    }

    public function pay(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('warning', 'คำสั่งซื้อนี้ไม่อยู่ในสถานะรอชำระเงิน');
        }

        return view('orders.pay', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);

        $updated = DB::table('orders')
            ->where('id', $order->id)
            ->where('status', 'pending')
            ->update(['status' => 'paid', 'updated_at' => now()]);   // ← ถ้าอยากให้เป็น completed เปลี่ยน 'paid' ตรงนี้

        if ($updated === 0) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'คำสั่งซื้อนี้ไม่อยู่ในสถานะรอชำระเงิน');
        }

        return redirect()->route('orders.show', $order)->with('success', 'ชำระเงินสำเร็จ ขอบคุณที่ใช้บริการ!');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);

        try {
            DB::transaction(function () use ($order) {
                $current = DB::table('orders')->where('id', $order->id)->lockForUpdate()->first();

                if ($current->status !== 'pending') {
                    throw new \DomainException('คำสั่งซื้อนี้ไม่สามารถยกเลิกได้แล้ว');
                }

                $items = DB::table('order_items')->where('order_id', $order->id)->get();

                foreach ($items as $item) {
                    DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);
                }

                DB::table('orders')->where('id', $order->id)
                    ->update(['status' => 'canceled', 'updated_at' => now()]);
            });
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'ยกเลิกคำสั่งซื้อแล้ว');
    }

    private function ensureOwner(Request $request, Order $order): void
    {
        abort_unless($order->user_id === $request->user()->id, 404);
    }
}