<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{

    protected $fillable = [
        'team_id',
        'setting_id',
        'value',
        'data',
    ];
    protected $casts = [
        "value" => "array",
        "data" => "array"
    ];

    // relation to settings.
    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_id');
    }

    // relation to tenant.
    public function tenant()
    {
        return $this->belongsTo(Team::class);
    }

    public function getSettingKeyAttribute()
    {
        return $this->setting?->key;
    }

    public function getSettingValueAttribute()
    {
        return $this->setting?->value;
    }
}
