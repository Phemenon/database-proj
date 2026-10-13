<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart ?: $user->cart()->create();
        
        $products = $cart->products;

        $totalPrice = $products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        return view('cart.index', compact('products', 'totalPrice'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $user = Auth::user();
        $cart = $user->cart ?: $user->cart()->create();

        $existing = $cart->products()->where('product_id', $product->id)->first();

        if ($existing) {
            $newQuantity = $existing->pivot->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Total requested quantity exceeds available stock.');
            }
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);
        } else {
            $cart->products()->attach($product->id, ['quantity' => $quantity]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to your cart.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = (int) $request->input('quantity');

        if ($quantity > $product->stock) {
            return back()->with('error', 'Quantity exceeds available stock.');
        }

        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(Product $product)
    {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->products()->detach($product->id);
        }

        return back()->with('success', 'Item removed from your cart.');
    }
}