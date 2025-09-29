<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Vite;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {

            // Register custom theme stylesheet via Vite
            Filament::registerViteTheme('resources/css/filament/theme.css');

            // Register all navigation items with groups
            Filament::registerNavigationItems([
                // User Management
                NavigationItem::make('Users')
                    ->url(\App\Filament\Resources\UserResource::getUrl())
                    ->icon('heroicon-o-user')
                    ->group('User Management'),

                NavigationItem::make('Roles')
                    ->url(\App\Filament\Resources\RoleResource::getUrl())
                    ->icon('heroicon-o-shield-check')
                    ->group('User Management'),

                // Inventory
                NavigationItem::make('Categories')
                    ->url(\App\Filament\Resources\CategoryResource::getUrl())
                    ->icon('heroicon-o-tag')
                    ->group('Inventory'),

                NavigationItem::make('Products')
                    ->url(\App\Filament\Resources\ProductResource::getUrl())
                    ->icon('heroicon-o-cube')
                    ->group('Inventory'),

                NavigationItem::make('Product Variants')
                    ->url(\App\Filament\Resources\ProductVariantResource::getUrl())
                    ->icon('heroicon-o-cog')
                    ->group('Inventory'),

                NavigationItem::make('Warehouses')
                    ->url(\App\Filament\Resources\WarehouseResource::getUrl())
                    ->icon('heroicon-o-office-building')
                    ->group('Inventory'),

                NavigationItem::make('Stock Movements')
                    ->url(\App\Filament\Resources\StockMovementResource::getUrl())
                    ->icon('heroicon-o-switch-horizontal')
                    ->group('Inventory'),

                // Business Partners
                NavigationItem::make('Customers')
                    ->url(\App\Filament\Resources\CustomerResource::getUrl())
                    ->icon('heroicon-o-users')
                    ->group('Business Partners'),

                NavigationItem::make('Suppliers')
                    ->url(\App\Filament\Resources\SupplierResource::getUrl())
                    ->icon('heroicon-o-truck')
                    ->group('Business Partners'),

                // Sales & Purchases
                NavigationItem::make('Orders')
                    ->url(\App\Filament\Resources\OrderResource::getUrl())
                    ->icon('heroicon-o-shopping-cart')
                    ->group('Sales & Purchases'),

                NavigationItem::make('Order Items')
                    ->url(\App\Filament\Resources\OrderItemResource::getUrl())
                    ->icon('heroicon-o-collection')
                    ->group('Sales & Purchases'),

                NavigationItem::make('Purchases')
                    ->url(\App\Filament\Resources\PurchaseResource::getUrl())
                    ->icon('heroicon-o-cash')
                    ->group('Sales & Purchases'),

                NavigationItem::make('Purchase Items')
                    ->url(\App\Filament\Resources\PurchaseItemResource::getUrl())
                    ->icon('heroicon-o-collection')
                    ->group('Sales & Purchases'),

                NavigationItem::make('Sales')
                    ->url(\App\Filament\Resources\SaleResource::getUrl())
                    ->icon('heroicon-o-currency-dollar')
                    ->group('Sales & Purchases'),

                NavigationItem::make('Sale Items')
                    ->url(\App\Filament\Resources\SaleItemResource::getUrl())
                    ->icon('heroicon-o-collection')
                    ->group('Sales & Purchases'),

                // Finance & Reports
                NavigationItem::make('Expenses')
                    ->url(\App\Filament\Resources\ExpenseResource::getUrl())
                    ->icon('heroicon-o-document-text')
                    ->group('Finance & Reports'),

                NavigationItem::make('Audit Logs')
                    ->url(\App\Filament\Resources\AuditLogResource::getUrl())
                    ->icon('heroicon-o-book-open')
                    ->group('Finance & Reports'),
            ]);
        });
    }
}
