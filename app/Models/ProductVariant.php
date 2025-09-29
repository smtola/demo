<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'attribute_name', 'attribute_value', 'sku', 'additional_price', 'image_url'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
