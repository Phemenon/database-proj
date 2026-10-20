<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart  = $request->user()->cart;
        $items = $cart ? $cart->products : collect();

        return view('cart.index', [
            'items'         => $items,
            'total'         => $items->sum(fn ($p) => $p->price * $p->pivot->quantity),
            'hasStockIssue' => $items->contains(fn ($p) => $p->pivot->quantity > $p->stock),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // exists:products,id ตรวจว่า product_id ที่ส่งมามีอยู่จริงในตาราง ป้องกันคนแก้ค่า hidden input ในฟอร์ม
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stock <= 0) {
            return back()->with('error', 'Out of stock');
        }

        $cart = $request->user()->cart()->firstOrCreate();

        $current = $cart->products()->where('products.id', $product->id)->first()?->pivot->quantity ?? 0;
        $wanted  = $current + $data['quantity'];
        $final   = min($wanted, $product->stock);

        // ถ้าสินค้านี้ยังไม่อยู่ในตะกร้าจะเพิ่มแถวใหม่ ถ้ามีอยู่แล้วจะอัปเดตจำนวน โดยไม่ลบสินค้าชิ้นอื่น จึงไม่ต้องเขียน if แยกเอง
        $cart->products()->syncWithoutDetaching([$product->id => ['quantity' => $final]]);

        if ($final < $wanted) {
            return back()->with('warning', "Quantity exceeds available stock. Adjusted to {$final} items.");
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        $cart = $request->user()->cart;
        abort_unless($cart && $cart->products()->where('products.id', $product->id)->exists(), 404);

        if ($product->stock <= 0) {
            return back()->with('error', 'Out of stock. Please remove it from the cart.');
        }

        $final = min($data['quantity'], $product->stock);
        $cart->products()->updateExistingPivot($product->id, ['quantity' => $final]);

        return back()->with(
            $final < $data['quantity'] ? 'warning' : 'success',
            $final < $data['quantity'] ? "Only {$final} items available. Adjusted accordingly." : 'Quantity updated successfully.'
        );
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->cart?->products()->detach($product->id);

        return back()->with('success', 'Item removed from cart.');
    }
}