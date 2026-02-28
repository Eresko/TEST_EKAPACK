<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название продукта');
            $table->string('sku')->unique()->comment('Код товара, уникальный');
            $table->decimal('price', 10, 2)->comment('Цена');
            $table->integer('stock_quantity')->comment('Количество');
            $table->string('category')->comment('Категория');
            $table->timestamps();
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
