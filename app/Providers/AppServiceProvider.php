<?php

namespace App\Providers;

use App\Models\DispatchItems;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Team;
use App\Observers\DispatchItemsObserver;
use Illuminate\Support\Facades\Gate;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Repositories\RoleRepository::class, function ($app) {
            return new \App\Repositories\RoleRepository();
        });

        $this->app->singleton(\App\Services\RoleService::class, function ($app) {
            return new \App\Services\RoleService($app->make(\App\Repositories\RoleRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super-Admin') ? true : null;
        });

        // Suponiendo que hay un team seleccionado (ej. en la sesión)
        /* $teamId = session('team_id', 1); // Asegúrate de que existe

        app(PermissionRegistrar::class)->setPermissionsTeamId($teamId); */

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn(): string => Blade::render('@livewire(\'footer-text-component\')'),
        );
        // Observers
        DispatchItems::observe(DispatchItemsObserver::class);
    }
}
