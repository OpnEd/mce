<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByTeam
{

    public static function boot()
    {
        parent::boot();

        $currentTeamID = auth()->user()->teams()->first()->id;
        $currentUserID = auth()->id();

        self::creating(function($model) use($currentTeamID, $currentUserID) {
            $model->team_id = $currentTeamID;
            $model->user_id = $currentUserID;
        });

        self::addGlobalScope(function(Builder $builder) use($currentTeamID) {
            $builder->where('team_id', $currentTeamID);
        });
    }
}
