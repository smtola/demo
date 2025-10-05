<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'type', 'quantity', 
        'cost_price', 'selling_price', 'warehouse_id', 'movement_date', 'note'
    ];

    protected $casts = [
        'movement_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    protected static function booted(): void
    {
        static::created(function (StockMovement $movement) {
            $product = $movement->product;
            if (! $product) {
                return;
            }

            // Only update quantity if this is a manual stock movement (not from product creation/update)
            // This prevents double-counting when we manually create stock movements
            if ($movement->note && !str_contains($movement->note, 'Initial stock') && !str_contains($movement->note, 'Stock in - Quantity increased')) {
                $currentQty = (int) $product->quantity_available;
                $newQty = $movement->type === 'in'
                    ? $currentQty + (int) $movement->quantity
                    : $currentQty - (int) $movement->quantity;

                $product->quantity_available = $newQty;
                // Use save() to fire Eloquent updated event so ProductObserver runs
                $product->save();
            }
        });
    }
}
