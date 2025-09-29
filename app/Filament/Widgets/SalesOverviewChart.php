<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Sale;

class SalesOverviewChart extends LineChartWidget
{
    protected static ?string $heading = 'Sales (Last 7 Days)';
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 3,
        'lg' => 2,
    ];

    protected function getData(): array
    {
        $sales = Sale::query()
            ->selectRaw('DATE(sale_date) as date, SUM(total_amount) as total_amount')
            ->where('sale_date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total_amount', 'date');

        return [
            'datasets' => [
                ['label' => 'Sales', 'data' => array_values($sales->toArray())],
            ],
            'labels' => array_keys($sales->toArray()),
        ];
    }
}
