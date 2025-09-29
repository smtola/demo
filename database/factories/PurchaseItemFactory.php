<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    protected $model = PurchaseItem::class;

    public function definition()
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 20);
        $costPrice = $product->cost_price;

        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'cost_price' => $costPrice,
            'subtotal' => $quantity * $costPrice,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
