<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = \App\Models\ProductVariant::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'attribute_name' => $this->faker->randomElement(['Size', 'Color']),
            'attribute_value' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'Red', 'Blue', 'Green']),
            'sku' => $this->faker->unique()->bothify('VAR-####'),
            'additional_price' => $this->faker->randomFloat(2, -5, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
