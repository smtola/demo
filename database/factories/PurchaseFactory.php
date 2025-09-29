<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(),
            'reference' => 'PO-' . $this->faker->unique()->numerify('#####'),
            'total_amount' => $this->faker->randomFloat(2, 100, 5000),
            'status' => $this->faker->randomElement(['draft', 'received', 'cancelled']),
            'purchase_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
