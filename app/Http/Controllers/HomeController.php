<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $base = fn () => Product::with('category')->withAvg('reviews', 'rating');

        return view('home', [
            // สินค้าล่าสุด 8 ชิ้น
            'latest' => $base()->orderByDesc('id')->take(8)->get(),

            // สินค้าเด่น: epic/legendary ที่ยังมีของ
            'featured' => $base()
                ->whereIn('rarity', ['epic', 'legendary'])
                ->where('stock', '>', 0)
                ->orderByDesc('price')
                ->take(4)
                ->get(),

            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}