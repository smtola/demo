<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SalesOverviewChart;
use App\Filament\Widgets\ExpensesOverviewChart;
use App\Filament\Widgets\LowStockProductsWidget;
use App\Filament\Widgets\StockMovementsOverview;
use App\Filament\Widgets\StockMovementsChart;
use App\Filament\Widgets\RecentStockMovements;
use Filament\Pages\Page;

class DashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard-page';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            StockMovementsOverview::class,
            SalesOverviewChart::class,
            ExpensesOverviewChart::class,
            StockMovementsChart::class,
            LowStockProductsWidget::class,
            RecentStockMovements::class,
        ];
    }

    public function getColumn(): int | string | array
    {
        return 2;
    }
}
