<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Products';

    protected int | bool $pollingInterval = 5; // no polling, real-time via Livewire
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 3,
        'lg' => 2,
    ];
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $threshold = 5;
        return Product::query()
            ->where('quantity_available', '<=', $threshold)
            ->orderBy('quantity_available', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Product Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('quantity_available')
                ->label('Available')
                ->sortable(),

            BadgeColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn ($record) => $record->quantity_available <= 0 ? 'Out of Stock' : 'Low Stock')
                ->colors([
                    'danger' => fn ($state) => $state === 'Out of Stock',
                    'warning' => fn ($state) => $state === 'Low Stock',
                ]),
        ];
    }
}
