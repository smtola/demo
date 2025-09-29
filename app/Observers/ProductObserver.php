<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Filament\Resources\ProductResource;
use Filament\Notifications\Actions\Action;

class ProductObserver
{
    /**
     * Handle the Product "created" event. 
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Only check if quantity_available changed
        if ($product->wasChanged('quantity_available')) {
            $newQty = (int) $product->quantity_available;
            $oldQty = (int) $product->getOriginal('quantity_available');
            $threshold = 5;

            // Notify when crossing from at/above threshold to below threshold
            if ($oldQty >= $threshold && $newQty < $threshold) {
                $this->sendLowStockNotification($product, $newQty, $threshold);
            }
        }
    }

    /**
     * Send low stock notification to admins
     */
    private function sendLowStockNotification(Product $product, int $currentQty, int $threshold): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        
        // If no admins found, try to get users with admin role directly
        if ($admins->isEmpty()) {
            $admins = User::where('role_id', function($query) {
                $query->select('id')
                      ->from('roles')
                      ->where('name', 'admin');
            })->get();
        }
        
        // Fallback to current user if no admins found
        if ($admins->isEmpty() && auth()->check()) {
            $admins = collect([auth()->user()]);
        }
        
        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                Notification::make()
                    ->title('Low Stock Alert')
                    ->body(sprintf(
                        'Product "%s" is running low on stock: %d remaining (threshold: %d). Please consider restocking.',
                        $product->name,
                        $currentQty,
                        $threshold
                    ))
                    ->danger()
                    ->actions([
                        Action::make('view_product')
                            ->label('View Product')
                            ->url(ProductResource::getUrl('edit', ['record' => $product->id]))
                            ->openUrlInNewTab()
                    ])
                    ->sendToDatabase($admin);
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
