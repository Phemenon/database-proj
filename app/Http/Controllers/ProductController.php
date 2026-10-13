<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $products = Product::with('category')
        ->withAvg('reviews', 'rating')
        ->when($request->category, fn ($q, $id) => $q->where('category_id', $id))
        ->orderBy('id')
        ->paginate(12)
        ->withQueryString();

    return view('products.index', [
        'products'    => $products,
        'categories'  => Category::orderBy('name')->get(),
        // id สินค้าที่อยู่ใน wishlist ของคนที่ login (ว่างถ้ายังไม่ login)
        'wishlistIds' => $request->user()
            ? $request->user()->wishlist()->pluck('products.id')
            : collect(),
    ]);
}

public function show(Request $request, Product $product)
{
    $product->load(['category', 'reviews.user']);

    return view('products.show', [
        'product'    => $product,
        'inWishlist' => $request->user()
            ? $request->user()->wishlist()->where('products.id', $product->id)->exists()
            : false,
    ]);
}
}