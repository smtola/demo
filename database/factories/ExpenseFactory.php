<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'user_id' => User::factory(),
            'expense_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'note' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
