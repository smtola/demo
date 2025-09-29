<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_info', 'email', 'phone', 'payment_terms', 'credit_limit', 'address'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
