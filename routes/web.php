<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

//---------- Products ----------
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{product}', 'show')->name('products.show');
});

// ---------- not authenticated ----------
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// ---------- authenticated ----------
Route::middleware('auth')->group(function () {
    //---------- Cart ----------
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{product}', [CartController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');
    //---------- Wishlist ----------
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

//---------- temporary route for testing flash messages ----------
Route::get('/flash-test', fn () => redirect()->route('home')->with('success', 'added to cart!'));