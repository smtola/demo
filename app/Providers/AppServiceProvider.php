<?php

namespace App\Providers;
use Livewire\Livewire;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\User;
use App\Observers\UserObserver;
use App\Observers\AuditObserver;
use App\Observers\RoleObserver;
use App\Observers\CategoryObserver;
use App\Observers\CustomerObserver;
use App\Observers\SupplierObserver;
use App\Observers\SaleObserver;
use App\Observers\PurchaseObserver;
use App\Observers\StockMovementObserver;
use App\Observers\ExpenseObserver;
use App\Observers\ProductVariantObserver;
use App\Observers\WarehouseObserver;
use App\Observers\PurchaseItemObserver;
use App\Observers\SaleItemObserver;
use App\Observers\SupplierProductObserver;
use App\Services\PayWayServices;
use App\Models\Role;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Expense;
use App\Models\AuditLog;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\SaleItem;
use App\Livewire\PrintReceipt;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PayWayServices::class, function($app) {
            // If PayWayServices has dependencies, you can resolve them from $app
            return new PayWayServices();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Product::observe(AuditObserver::class);
        User::observe(UserObserver::class);
        User::observe(AuditObserver::class);
        Role::observe(AuditObserver::class);
        Category::observe(AuditObserver::class);
        Customer::observe(AuditObserver::class);
        Sale::observe(AuditObserver::class);
        StockMovement::observe(AuditObserver::class);
        Expense::observe(AuditObserver::class);
        SaleItem::observe(AuditObserver::class);
        Warehouse::observe(AuditObserver::class);
        ProductVariant::observe(AuditObserver::class);
        Livewire::component('print-receipt', PrintReceipt::class);
        // Ensure proper asset URL for production and Vercel
        $appUrl = config('app.url');
        if (str_contains($appUrl, 'vercel.app') || app()->environment('production')) {
            // Force HTTPS for Vercel
            if (!str_starts_with($appUrl, 'https://')) {
                $appUrl = 'https://' . ltrim($appUrl, 'https://');
            }
            \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
    }
}
