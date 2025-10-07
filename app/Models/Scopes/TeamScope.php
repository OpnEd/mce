<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TeamScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    /* public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check() && Auth::user()->currentTeam) {
            $teamId = Auth::user()->currentTeam->id;
            $builder->where($model->getTable() . '.team_id', $teamId);
        }
    } */
   /* public function apply(Builder $builder, Model $model)
    {
        // Intentos para resolver team id: auth user, tenant helper, etc.
        $teamId = null;

        try {
            if (auth()->check()) {
                $user = auth()->user();
                if (isset($user->team_id)) {
                    $teamId = $user->team_id;
                } elseif (isset($user->current_team_id)) {
                    $teamId = $user->current_team_id;
                }
            }
        } catch (\Throwable $e) {}

        if (empty($teamId) && function_exists('tenant')) {
            try {
                $t = tenant();
                if (is_object($t) && isset($t->id)) {
                    $teamId = $t->id;
                } elseif (is_array($t) && isset($t['id'])) {
                    $teamId = $t['id'];
                }
            } catch (\Throwable $e) {}
        }

        if ($teamId) {
            $builder->where($model->getTable() . '.team_id', $teamId);
        }
    } */
}
