<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->string('image_url')->nullable();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE products ADD CONSTRAINT chk_products_price CHECK (price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
