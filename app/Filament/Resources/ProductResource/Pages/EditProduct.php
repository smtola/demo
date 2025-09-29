<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
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
}
