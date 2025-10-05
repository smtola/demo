<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->font('Noto Sans Khmer', null, \Filament\FontProviders\LocalFontProvider::class)
            ->login()
            ->passwordReset()
            ->profile()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\DashboardPage::class,
            ])
            
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->renderHook(
                'panels::auth.login.form.after',
                fn (): string => view('filament.auth.debug-info'),
            );
    }
}
