<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FrontendDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('products')->exists()) {
            $this->command->warn('products มีข้อมูลอยู่แล้ว ข้าม seeder นี้');
            return;
        }

        $now = now();

        $categoryIds = [];
        foreach (['Weapons', 'Armor', 'Potions', 'Accessories', 'Materials'] as $name) {
            $categoryIds[$name] = DB::table('categories')->insertGetId([
                'name' => $name, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // [ชื่อ, หมวดหมู่, rarity, ราคา, stock]
        $items = [
            ['Celestial Blade',  'Weapons',     'legendary', 2490, 5],
            ['Dragon Shield',    'Armor',       'epic',      1890, 3],
            ['Healing Potion',   'Potions',     'uncommon',   350, 40],
            ['Magic Staff',      'Weapons',     'rare',      2990, 2],
            ['Shadow Dagger',    'Weapons',     'uncommon',  1650, 0],
            ['Wizard Robe',      'Armor',       'epic',      2200, 6],
            ['Phoenix Feather',  'Materials',   'legendary', 1100, 8],
            ['Ring of Power',    'Accessories', 'legendary', 5500, 1],
        ];

        $productIds = [];
        foreach ($items as $i => [$name, $category, $rarity, $price, $stock]) {
            $productIds[$name] = DB::table('products')->insertGetId([
                'name'        => $name,
                'description' => "A fine {$rarity} item from the {$category} collection.",
                'price'       => $price,
                'stock'       => $stock,
                'rarity'      => $rarity,
                'image_url'   => 'https://placehold.co/600x600/4a3328/faf6f0?text=' . urlencode($name),
                'category_id' => $categoryIds[$category],
                'created_at'  => now()->subDays(count($items) - $i),
                'updated_at'  => $now,
            ]);
        }

        $userIds = [];
        foreach (['John Doe', 'KnightRaven', 'Luna'] as $n => $name) {
            $userIds[] = DB::table('users')->insertGetId([
                'name'       => $name,
                'email'      => 'demo' . ($n + 1) . '@example.com',
                'password'   => Hash::make('password'),
                'phone'      => '0812345678',
                'address'    => '123/45 Chiang Mai 50200',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // [สินค้า, index ของ user, คะแนน, ความเห็น]
        $reviews = [
            ['Celestial Blade', 0, 5, 'Absolutely worth it!'],
            ['Celestial Blade', 1, 4, 'Great blade, slightly heavy.'],
            ['Celestial Blade', 2, 5, 'Looks stunning.'],
            ['Dragon Shield',   0, 4, 'Solid protection.'],
            ['Healing Potion',  1, 3, 'Does the job.'],
            ['Wizard Robe',     2, 5, 'Very comfortable.'],
        ];

        foreach ($reviews as [$product, $u, $rating, $comment]) {
            DB::table('reviews')->insert([
                'user_id'    => $userIds[$u],
                'product_id' => $productIds[$product],
                'rating'     => $rating,
                'comment'    => $comment,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}