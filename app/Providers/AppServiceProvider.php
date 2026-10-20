<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', function ($view) {
            $cartCount = auth()->check()
                ? (int) auth()->user()->cart?->products()->sum('cart_items.quantity')
                : 0;

            $view->with('categories', Category::orderBy('name')->get())
                ->with('cartCount', $cartCount);
        });

        View::composer(['home', 'products.index', 'products.show', 'wishlist.index'], function ($view) {
            $view->with('wishlistedIds', auth()->check()
                ? auth()->user()->wishlistProducts()->pluck('products.id')->all()
                : []);
        });
    }
}
