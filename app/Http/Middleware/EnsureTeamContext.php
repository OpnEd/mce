<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Team;
use Filament\Facades\Filament;

class EnsureTeamContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay un tenant en la ruta
        $tenant = Filament::getTenant();
        
        if (!$tenant && $request->route('tenant')) {
            // Si no hay tenant pero existe en la ruta, establecerlo
            $tenantModel = \App\Models\Team::find($request->route('tenant'));
            if ($tenantModel) {
                Filament::setTenant($tenantModel);
            }
        }

        /* // 1) Obtener el tenant actual
        $team = Filament::getTenant();
        abort_unless($team, 404);

        // 2) Verificar pertenencia
        $user = $request->user();
        abort_unless($user && $user->teams()->where('teams.id', $team->id)->exists(), 403);

        // 3) Binder
        app()->instance(Team::class, $team);

        // O si prefieres un singleton:
        // app()->singleton(Team::class, fn() => $team); */
        return $next($request);
    }
}
