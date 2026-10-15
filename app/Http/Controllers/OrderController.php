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
        abort_unless($order->user_id === $request->user()->id, 404);

        $items = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('order_items.order_id', $order->id)
            ->orderBy('order_items.item_no')
            ->select('order_items.*', 'products.name', 'products.image_url')
            ->get();

        return view('orders.show', compact('order', 'items'));
    }
}