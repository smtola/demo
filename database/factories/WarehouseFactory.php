<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = \App\Models\Warehouse::class;

    public function definition()
    {
        return [
            'name' => 'Warehouse ' . $this->faker->word(),
            'location' => $this->faker->city(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
