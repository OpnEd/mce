<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\SetTeamPermissions;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class PosPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pos')
            ->path('pos')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Pos/Resources'), for: 'App\\Filament\\Pos\\Resources')
            ->discoverPages(in: app_path('Filament/Pos/Pages'), for: 'App\\Filament\\Pos\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->tenant(Team::class)
            ->discoverWidgets(in: app_path('Filament/Pos/Widgets'), for: 'App\\Filament\\Pos\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->login()
            ->registration()
            ->resources([
                \App\Filament\Resources\BatchResource::class,
                \App\Filament\Resources\EnvironmentalRecordResource::class,
                \App\Filament\Resources\CustomerResource::class,
                \App\Filament\Resources\PurchaseResource::class,
                \App\Filament\Resources\PurchaseItemResource::class,
                \App\Filament\Resources\ProductReceptionResource::class,
                \App\Filament\Resources\ProductReceptionItemResource::class,
            ])
            ->passwordReset()
            //->emailVerification()
            ->profile()
            ->databaseNotifications()
            ->tenantMiddleware([
                SetTeamPermissions::class,
                ApplyTenantScopes::class,
                CustomerMiddleware::class,
            ], isPersistent: true)
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn(): string => Blade::render('@livewire(\'public-health-go-button\')'),
            );
    }
}
