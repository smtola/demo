<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = \App\Models\Supplier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'contact_info' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'payment_terms' => $this->faker->randomElement(['Net 30', 'Net 60']),
            'credit_limit' => $this->faker->randomFloat(2, 1000, 10000),
            'address' => $this->faker->address(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
