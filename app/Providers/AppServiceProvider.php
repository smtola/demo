<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\User;
use App\Observers\UserObserver;
use App\Services\PayWayServices;


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
        User::observe(UserObserver::class);
        
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
