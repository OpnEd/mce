<?php

namespace App\Traits;

use App\Models\Scopes\TeamScope;
use App\Support\Tenancy\ResolvesCurrentTeamId;
use Illuminate\Database\Eloquent\Builder;

trait HasTeamScope
{
    public static function bootHasTeamScope(): void
    {
        static::addGlobalScope(new TeamScope());

        static::creating(function ($model): void {
            if (empty($model->team_id)) {
                $model->team_id = ResolvesCurrentTeamId::resolve();
            }
        });
    }

    public function scopeForTeam(Builder $query, int $teamId): Builder
    {
        return $query
            ->withoutGlobalScope(TeamScope::class)
            ->where($this->getTable() . '.team_id', $teamId);
    }

    public function scopeForCurrentTeam(Builder $query): Builder
    {
        $teamId = ResolvesCurrentTeamId::resolve();

        if ($teamId === null) {
            return $query;
        }

        return $query->forTeam($teamId);
    }
}
