<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Weapons', 'Armor', 'Potions', 'Accessories', 'Scrolls'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}