<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockMovementsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();
        $previousMonth = $now->copy()->subMonth();

        // --- Total Stock In ---
        $totalStockIn = StockMovement::where('type', 'in')->sum('quantity');
        $lastMonthStockIn = StockMovement::where('type', 'in')
            ->whereYear('movement_date', $previousMonth->year)
            ->whereMonth('movement_date', $previousMonth->month)
            ->sum('quantity');

        $stockInChange = $lastMonthStockIn > 0
            ? (($totalStockIn - $lastMonthStockIn) / $lastMonthStockIn) * 100
            : 0;

        $stockInColor = $stockInChange >= 0 ? 'success' : 'danger';
        $stockInDescription = number_format(abs($stockInChange), 2) . '% ' . ($stockInChange >= 0 ? 'increase' : 'decrease');

        // --- Total Stock Out ---
        $totalStockOut = StockMovement::where('type', 'out')->sum('quantity');
        $lastMonthStockOut = StockMovement::where('type', 'out')
            ->whereYear('movement_date', $previousMonth->year)
            ->whereMonth('movement_date', $previousMonth->month)
            ->sum('quantity');

        $stockOutChange = $lastMonthStockOut > 0
            ? (($totalStockOut - $lastMonthStockOut) / $lastMonthStockOut) * 100
            : 0;

        $stockOutColor = $stockOutChange >= 0 ? 'danger' : 'success'; // Opposite logic for stock out
        $stockOutDescription = number_format(abs($stockOutChange), 2) . '% ' . ($stockOutChange >= 0 ? 'increase' : 'decrease');

        // --- Net Stock Movement ---
        $netStockMovement = $totalStockIn - $totalStockOut;
        $lastMonthNetStock = $lastMonthStockIn - $lastMonthStockOut;
        
        $netStockChange = $lastMonthNetStock != 0
            ? (($netStockMovement - $lastMonthNetStock) / abs($lastMonthNetStock)) * 100
            : 0;

        $netStockColor = $netStockMovement >= 0 ? 'success' : 'danger';
        $netStockDescription = $netStockMovement >= 0 ? 'Net positive' : 'Net negative';

        // --- Total Products ---
        $totalProducts = Product::count();
        $lastMonthProducts = Product::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        $productsChange = $lastMonthProducts > 0
            ? (($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100
            : 0;

        $productsColor = $productsChange >= 0 ? 'success' : 'danger';
        $productsDescription = number_format(abs($productsChange), 2) . '% ' . ($productsChange >= 0 ? 'increase' : 'decrease');

        // --- Last 7 Days Charts ---
        $daysRange = range(6, 0, -1);

        $stockInLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => StockMovement::where('type', 'in')
                ->whereDate('movement_date', $now->copy()->subDays($daysAgo))
                ->sum('quantity'))
            ->toArray();

        $stockOutLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => StockMovement::where('type', 'out')
                ->whereDate('movement_date', $now->copy()->subDays($daysAgo))
                ->sum('quantity'))
            ->toArray();

        $netStockLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => 
                StockMovement::where('type', 'in')
                    ->whereDate('movement_date', $now->copy()->subDays($daysAgo))
                    ->sum('quantity') - 
                StockMovement::where('type', 'out')
                    ->whereDate('movement_date', $now->copy()->subDays($daysAgo))
                    ->sum('quantity'))
            ->toArray();

        $productsLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => Product::whereDate('created_at', $now->copy()->subDays($daysAgo))->count())
            ->toArray();

        return [
            Stat::make('Total Stock In', number_format($totalStockIn))
                ->description($stockInDescription)
                ->descriptionIcon($stockInChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($stockInLast7Days)
                ->color($stockInColor)
                ->icon('heroicon-o-arrow-down-tray'),

            Stat::make('Total Stock Out', number_format($totalStockOut))
                ->description($stockOutDescription)
                ->descriptionIcon($stockOutChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($stockOutLast7Days)
                ->color($stockOutColor)
                ->icon('heroicon-o-arrow-up-tray'),

            Stat::make('Net Stock Movement', number_format($netStockMovement))
                ->description($netStockDescription)
                ->descriptionIcon($netStockMovement >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($netStockLast7Days)
                ->color($netStockColor)
                ->icon('heroicon-o-scale'),

            Stat::make('Total Products', number_format($totalProducts))
                ->description($productsDescription)
                ->descriptionIcon($productsChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($productsLast7Days)
                ->color($productsColor)
                ->icon('heroicon-o-cube'),
        ];
    }
}
