<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition()
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'sale_id' => Sale::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'selling_price' => $product->unit_price,
            'subtotal' => $quantity * $product->unit_price,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
