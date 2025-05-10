<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;
use Spatie\Permission\PermissionRegistrar;

class SetTeamPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Intenta obtener el tenant actual usando Filament.
        $team = Filament::getTenant();

        if ($team) {
            // Configura el team id para Spatie.
            // Esto hace que todas las comprobaciones de permisos (hasPermissionTo, etc.)
            // se filtren automáticamente según el tenant actual.
            app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);
        }


        return $next($request);
    }
}
