<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['create', 'update', 'delete']),
            'entity_type' => $this->faker->randomElement(['Product', 'Order', 'Sale', 'Customer']),
            'entity_id' => $this->faker->numberBetween(1, 100),
            'data' => json_encode(['field' => 'value', 'changes' => ['old' => 'old_value', 'new' => 'new_value']]),
            'performed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
