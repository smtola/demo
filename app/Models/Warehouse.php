<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
