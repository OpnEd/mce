<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByTeam
{

    public static function boot()
    {
        parent::boot();

        $currentTenantID = auth()->user()->teams()->first()->id;
        $currentUserID = auth()->id();

        self::creating(function($model) use($currentTenantID, $currentUserID) {
            $model->tenant_id = $currentTenantID;
            $model->user_id = $currentUserID;
        });

        self::addGlobalScope(function(Builder $builder) use($currentTenantID) {
            $builder->where('tenant_id', $currentTenantID);
        });
    }
}
