<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Filament\Facades\Filament;

class TeamNotificationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $team = Filament::getTenant();
        
        if ($team && $model->getTable() === 'notifications') {
            $builder->where('team_id', $team->id);
        }
    }
}
