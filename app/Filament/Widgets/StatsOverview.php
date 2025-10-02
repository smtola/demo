<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();
        $previousMonth = $now->copy()->subMonth();

        // --- Revenue ---
        $totalRevenue = Sale::sum('total_amount');
        $lastMonthRevenue = Sale::whereYear('sale_date', $previousMonth->year)
            ->whereMonth('sale_date', $previousMonth->month)
            ->sum('total_amount');

        $revenueChange = $lastMonthRevenue > 0
            ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $revenueColor = $revenueChange >= 0 ? 'success' : 'danger';
        $revenueDescription = number_format(abs($revenueChange), 2) . '% ' . ($revenueChange >= 0 ? 'increase' : 'decrease');

        // --- New Customers ---
        $newCustomers = Customer::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $lastMonthCustomers = Customer::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        $customersChange = $lastMonthCustomers > 0
            ? (($newCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100
            : 0;

        $customersColor = $customersChange >= 0 ? 'success' : 'danger';
        $customersDescription = number_format(abs($customersChange), 2) . '% ' . ($customersChange >= 0 ? 'increase' : 'decrease');

        // --- New Orders ---
        $newOrders = Sale::whereMonth('sale_date', $now->month)
            ->whereYear('sale_date', $now->year)
            ->count();

        $lastMonthOrders = Sale::whereYear('sale_date', $previousMonth->year)
            ->whereMonth('sale_date', $previousMonth->month)
            ->count();

        $ordersChange = $lastMonthOrders > 0
            ? (($newOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : 0;

        $ordersColor = $ordersChange >= 0 ? 'success' : 'danger';
        $ordersDescription = number_format(abs($ordersChange), 2) . '% ' . ($ordersChange >= 0 ? 'increase' : 'decrease');

        // --- Last 7 Days Charts ---
        $daysRange = range(6, 0, -1);

        $ordersLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => Sale::whereDate('sale_date', $now->subDays($daysAgo))->count())
            ->toArray();

        $customersLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => Customer::whereDate('created_at', $now->subDays($daysAgo))->count())
            ->toArray();

        $revenueLast7Days = collect($daysRange)
            ->map(fn($daysAgo) => Sale::whereDate('sale_date', $now->subDays($daysAgo))->sum('total_amount'))
            ->toArray();

        return [
            Stat::make('Revenue', '$' . number_format($totalRevenue / 1000, 2) . 'K')
                ->description($revenueDescription)
                ->descriptionIcon($revenueChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($revenueLast7Days)
                ->color($revenueColor),

            Stat::make('New Customers', number_format($newCustomers / 1000, 2) . 'K')
                ->description($customersDescription)
                ->descriptionIcon($customersChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($customersLast7Days)
                ->color($customersColor),

            Stat::make('New Sales', number_format($newOrders / 1000, 2) . 'K')
                ->description($ordersDescription)
                ->descriptionIcon($ordersChange >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->chart($ordersLast7Days)
                ->color($ordersColor),
        ];
    }
}
