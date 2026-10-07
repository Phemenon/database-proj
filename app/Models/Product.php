<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'rarity', 'image_url', 'category_id'];

    protected $casts = ['price' => 'decimal:2'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ผู้ใช้ที่กดเก็บสินค้านี้ไว้ใน wishlist
    public function wishedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function averageRating(): ?float
    {
        return $this->reviews()->avg('rating');
    }
}
