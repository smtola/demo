<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'sku' => $this->faker->unique()->bothify('SKU-#######'),
            'category_id' => Category::factory(),
            'brand' => $this->faker->company(),
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
            'cost_price' => $this->faker->randomFloat(2, 5, 300),
            'quantity_available' => $this->faker->numberBetween(0, 100),
            'warehouse_id' => Warehouse::factory(),
            'barcode' => $this->faker->ean13(),
            'expiry_date' => $this->faker->dateTimeBetween('+1 month', '+2 years'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
