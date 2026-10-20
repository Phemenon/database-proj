<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->when($request->filled('category'), fn ($q) => $q->whereIn('category_id', (array) $request->category))
            ->when($request->filled('rarity'), fn ($q) => $q->where('rarity', $request->rarity))
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->max_price));

        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating'     => $query->orderByDesc('reviews_avg_rating'),
            default      => $query->latest(),
        };

        return view('products.index', [
            'products'   => $query->paginate(6)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'rarities'   => ['common', 'uncommon', 'rare', 'epic', 'legendary'],
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category')->loadAvg('reviews', 'rating')->loadCount('reviews');

        $reviews = $product->reviews()->with('user')->latest()->paginate(5);

        $userReview = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('products.show', compact('product', 'reviews', 'userReview'));
    }
}