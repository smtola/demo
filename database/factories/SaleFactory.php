<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'reference' => 'INV-' . $this->faker->unique()->numerify('#####'),
            'total_amount' => $this->faker->randomFloat(2, 50, 5000),
            'status' => $this->faker->randomElement(['draft', 'completed', 'cancelled']),
            'sale_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'customer_info' => $this->faker->name() . ', ' . $this->faker->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
