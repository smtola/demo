<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;

class PurchasesOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Purchases (Last 7 days)';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))
            ->map(fn ($i) => now()->subDays(6 - $i)->startOfDay());

        $counts = $dates->map(function ($date) {
            return Purchase::whereDate('purchase_date', $date)->count();
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Purchases',
                    'data' => $counts,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dates->map->format('M d')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}


