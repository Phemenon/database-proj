<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // [ชื่อ, หมวด, ราคา, stock, rarity, คำอธิบาย]
            ['Iron Sword',          'Weapons',     450.00,  25, 'common',    'ดาบเหล็กพื้นฐานสำหรับนักผจญภัยมือใหม่'],
            ['Elven Longbow',       'Weapons',    2800.00,  10, 'rare',      'ธนูไม้เอลฟ์ ยิงแม่นและเบา'],
            ['Dragonbane Blade',    'Weapons',   15000.00,   2, 'legendary', 'ดาบในตำนานที่ใช้สังหารมังกร'],
            ['Leather Armor',       'Armor',       800.00,  20, 'common',    'เกราะหนังเบา คล่องตัว'],
            ['Mithril Chainmail',   'Armor',      9500.00,   5, 'epic',      'เกราะโซ่มิธริล เบาแต่แข็งแกร่ง'],
            ['Knight Shield',       'Armor',      1200.00,  15, 'uncommon',  'โล่อัศวินตราสิงโต'],
            ['Healing Potion',      'Potions',     120.00, 100, 'common',    'ฟื้นฟูพลังชีวิตเล็กน้อย'],
            ['Elixir of Mana',      'Potions',     600.00,  40, 'uncommon',  'เพิ่มพลังเวทให้เต็มทันที'],
            ['Phoenix Tears',       'Potions',    7500.00,   3, 'legendary', 'ชุบชีวิตผู้ล้มลงได้หนึ่งครั้ง'],
            ['Ring of Wisdom',      'Accessories', 3200.00,   8, 'rare',      'แหวนเพิ่มสติปัญญา'],
            ['Amulet of the Moon',  'Accessories', 5400.00,   6, 'epic',      'เครื่องรางพลังแห่งจันทรา'],
            ['Scroll of Fireball',  'Scrolls',      350.00,  30, 'uncommon',  'ม้วนคัมภีร์เรียกลูกไฟ ใช้ได้ครั้งเดียว'],
        ];

        foreach ($items as [$name, $category, $price, $stock, $rarity, $description]) {
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => Category::where('name', $category)->value('id'),
                    'price'       => $price,
                    'stock'       => $stock,
                    'rarity'      => $rarity,
                    'description' => $description,
                ]
            );
        }
    }
}