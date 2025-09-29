<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SalesOverviewChart;
use App\Filament\Widgets\PurchasesOverviewChart;
use App\Filament\Widgets\ExpensesOverviewChart;
use App\Filament\Widgets\LowStockProductsWidget;
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
            SalesOverviewChart::class,
            PurchasesOverviewChart::class,
            ExpensesOverviewChart::class,
            LowStockProductsWidget::class,
        ];
    }

    public function getColumn(): int | string | array
    {
        return 2;
    }
}
