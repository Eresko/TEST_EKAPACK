<?php
// database/factories/ProductFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Product;
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'sku' => $this->faker->unique()->ean13(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'category' => $this->faker->randomElement(['Electronics', 'Books', 'Clothing', 'Home & Garden']),
        ];
    }
}