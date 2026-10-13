<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()
            ->wishlist()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->orderBy('products.name')
            ->get();

        return view('wishlist.index', ['products' => $products]);
    }

    public function store(Request $request, Product $product)
    {
        // ไม่ซ้ำ: ถ้าเพิ่มแล้ว กดซ้ำก็ไม่เกิดแถวใหม่ ไม่ error
        $request->user()->wishlist()->syncWithoutDetaching([$product->id]);

        return back()->with('status', 'เพิ่มลงรายการโปรดแล้ว');
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()->wishlist()->detach($product->id);

        return back()->with('status', 'นำออกจากรายการโปรดแล้ว');
    }
}