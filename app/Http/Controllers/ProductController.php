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
            'products'   => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);

        return view('products.show', compact('product'));
    }
}