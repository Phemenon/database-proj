<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // cart_items เป็นตารางเชื่อม ใช้เป็น pivot (มี quantity)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')->withPivot('quantity');
    }
}
