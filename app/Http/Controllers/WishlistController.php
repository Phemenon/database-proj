<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->wishlistProducts()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('wishlists.created_at')
            ->paginate(8);

        return view('wishlist.index', compact('products'));
    }

    public function toggle(Request $request, Product $product)
    {
        $exists = $request->user()->wishlistProducts()
            ->where('products.id', $product->id)
            ->exists();

        if ($exists) {
            $request->user()->wishlistProducts()->detach($product->id);

            return back()->with('success', 'sucessfuly removed from Wishlist');
        }

        $request->user()->wishlistProducts()->attach($product->id, ['created_at' => now()]);

        return back()->with('success', 'sucessfuly added to Wishlist');
    }
}