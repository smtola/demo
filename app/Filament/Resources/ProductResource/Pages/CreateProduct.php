<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\WithFileUploads;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    use WithFileUploads;
}
