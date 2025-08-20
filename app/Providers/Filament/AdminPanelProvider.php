<?php

namespace App\Providers\Filament;

use App\Filament\Pages\CustomLogin;
use App\Filament\Pages\Setting;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Settings;
use App\Filament\Widgets\MinutesIvc;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\SetTeamPermissions;
use Filament\Forms\Set;
use Filament\Navigation\MenuItem;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\EnsureTeamContext;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
                Setting::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //MinutesIvc::class,
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
                EnsureTeamContext::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->databaseNotifications()
            ->tenant(Team::class)
            ->tenantMenuItems([
                'profile' => MenuItem::make()->label('Edit team profile'),
                'register' => MenuItem::make()->label('Register new team'),
                /* MenuItem::make()
                    ->label('Settings')
                    ->url(fn(): string => Settings::getUrl())
                    ->icon('heroicon-m-cog-8-tooth'), */
                // ...
            ])
            ->tenantMiddleware([
                SetTeamPermissions::class,
                ApplyTenantScopes::class,
                CustomerMiddleware::class,
            ], isPersistent: true)
            ->tenantProfile(EditTeamProfile::class)
            ->tenantRegistration(RegisterTeam::class)
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Registros Diarios')
                    ->icon('phosphor-thermometer-hot'),
                NavigationGroup::make()
                    ->label('Universidad')
                    ->icon('phosphor-student'),
                NavigationGroup::make()
                    ->label('POS')
                    ->icon('phosphor-barcode'),
                NavigationGroup::make()
                    ->label('9. Sistema de Gestión de la Calidad')
                    ->icon('phosphor-presentation-chart'),
                NavigationGroup::make()
                    ->label('Roles y Permisos')
                    ->icon('phosphor-fingerprint'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('phosphor-gear-six'),
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn(): string => Blade::render('@livewire(\'iniciar-venta-button\')'),
            )
            ->spa();
    }
}
