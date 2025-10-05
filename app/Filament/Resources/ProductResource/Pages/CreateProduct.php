<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\StockMovement;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\WithFileUploads;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    use WithFileUploads;

    protected function afterCreate(): void
    {
        $product = $this->record;
        
        // Create stock movement for initial quantity if > 0
        if ($product->quantity_available > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $product->quantity_available,
                'cost_price' => $product->cost_price ?? 0,
                'selling_price' => $product->unit_price,
                'warehouse_id' => $product->warehouse_id,
                'movement_date' => now(),
                'note' => 'Initial stock - Product created'
            ]);
        }
    }
}
