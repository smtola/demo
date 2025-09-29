<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'sku', 'author', 'ISBN', 'category_id', 'brand', 'image_url',  'unit_price', 'cost_price', 
        'quantity_available', 'warehouse_id', 'barcode', 'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Get the full URL for the product image
     */
    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Use Storage facade to get the URL from S3
        return Storage::url($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products');
    }

    /**
     * Update quantity available based on stock movements
     */
    public function updateQuantityFromStockMovements(): void
    {
        $totalIn = $this->stockMovements()
            ->where('type', 'in')
            ->sum('quantity');
            
        $totalOut = $this->stockMovements()
            ->where('type', 'out')
            ->sum('quantity');
            
        $this->update([
            'quantity_available' => $totalIn - $totalOut
        ]);
    }
}
