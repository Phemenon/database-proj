<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $withStats = fn () => Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        return view('home', [
            'categories'  => Category::all(),
            'newArrivals' => $withStats()->latest()->take(4)->get(),
            'topRated'    => $withStats()->orderByDesc('reviews_avg_rating')->take(4)->get(),
        ]);
    }
}