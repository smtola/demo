<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use Filament\Widgets\ChartWidget;

class ExpensesOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Expenses (Last 7 days)';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))
            ->map(fn ($i) => now()->subDays(6 - $i)->startOfDay());

        $counts = $dates->map(function ($date) {
            return Expense::whereDate('expense_date', $date)->count();
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Expenses',
                    'data' => $counts,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
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


