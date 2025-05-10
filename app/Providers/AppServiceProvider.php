<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Team;

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
        // Suponiendo que hay un team seleccionado (ej. en la sesión)
        $teamId = session('team_id', 1); // Asegúrate de que existe

        app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);
    }
}
