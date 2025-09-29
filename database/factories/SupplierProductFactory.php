<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierProductFactory extends Factory
{
    protected $model = \App\Models\SupplierProduct::class;

    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'product_id' => Product::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
