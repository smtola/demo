<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['in', 'out']),
            'quantity' => $this->faker->numberBetween(1, 50),
            'cost_price' => $this->faker->randomFloat(2, 5, 300),
            'selling_price' => $this->faker->randomFloat(2, 10, 500),
            'warehouse_id' => Warehouse::factory(),
            'movement_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'note' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
