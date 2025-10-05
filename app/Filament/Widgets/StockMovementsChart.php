<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class StockMovementsChart extends ChartWidget
{
    protected static ?string $heading = 'Stock Movements Overview';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $now = Carbon::now();
        $days = 30; // Last 30 days
        
        $labels = [];
        $stockInData = [];
        $stockOutData = [];
        $netStockData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $labels[] = $date->format('M d');
            
            $stockIn = StockMovement::where('type', 'in')
                ->whereDate('movement_date', $date)
                ->sum('quantity');
            
            $stockOut = StockMovement::where('type', 'out')
                ->whereDate('movement_date', $date)
                ->sum('quantity');
            
            $stockInData[] = $stockIn;
            $stockOutData[] = $stockOut;
            $netStockData[] = $stockIn - $stockOut;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Stock In',
                    'data' => $stockInData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Stock Out',
                    'data' => $stockOutData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Net Stock',
                    'data' => $netStockData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}
