<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // PK = (user_id, product_id) 
    // EX Review::updateOrCreate([...user_id, product_id], [...rating, comment])
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['user_id', 'product_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
