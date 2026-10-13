<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // user_id มาจากคนที่ login เท่านั้น ไม่รับจากฟอร์ม
        $key = ['user_id' => $request->user()->id, 'product_id' => $product->id];

        $existing = Review::where($key);

        if ($existing->exists()) {
            $existing->update($data);          // แก้รีวิวเดิมของตัวเอง
        } else {
            Review::create($key + $data);      // รีวิวครั้งแรก
        }

        return redirect()->route('products.show', $product)->with('status', 'บันทึกรีวิวแล้ว');
    }

    public function destroy(Request $request, Product $product)
    {
        Review::where([
            'user_id'    => $request->user()->id,
            'product_id' => $product->id,
        ])->delete();

        return redirect()->route('products.show', $product)->with('status', 'ลบรีวิวแล้ว');
    }
}