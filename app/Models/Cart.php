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
        // withPivot('quantity') ปกติ Laravel ดึงจากตารางกลางมาแค่คอลัมน์คีย์ ถ้าไม่ใส่ จะอ่านจำนวนไม่ได้ ($product->pivot->quantity)
        return $this->belongsToMany(Product::class, 'cart_items')->withPivot('quantity');
    }
}
