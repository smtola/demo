<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\StockMovement;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\WithFileUploads;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    use WithFileUploads;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $product = $this->record;
        $originalQuantity = $this->getOriginal('quantity_available');
        $newQuantity = $product->quantity_available;
        
        // Calculate quantity difference
        $quantityDifference = $newQuantity - $originalQuantity;
        
        // Create stock movement if quantity increased
        if ($quantityDifference > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $quantityDifference,
                'cost_price' => $product->cost_price ?? 0,
                'selling_price' => $product->unit_price,
                'warehouse_id' => $product->warehouse_id,
                'movement_date' => now(),
                'note' => "Stock in - Quantity increased from {$originalQuantity} to {$newQuantity}"
            ]);
        }
        // Create stock movement if quantity decreased
        elseif ($quantityDifference < 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'out',
                'quantity' => abs($quantityDifference),
                'cost_price' => $product->cost_price ?? 0,
                'selling_price' => $product->unit_price,
                'warehouse_id' => $product->warehouse_id,
                'movement_date' => now(),
                'note' => "Stock out - Quantity decreased from {$originalQuantity} to {$newQuantity}"
            ]);
        }
    }
}
