<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            Role::addGlobalScope('team_or_global', function (Builder $query) use ($tenant) {
                // Solo incluye roles globales o del tenant actual
                $query->where(function (Builder $q) use ($tenant) {
                    $q->whereNull('team_id')
                      ->orWhere('team_id', '=', $tenant->id);
                });
            });
        }
        /* Role::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        ); */
        return $next($request);
    }
}
