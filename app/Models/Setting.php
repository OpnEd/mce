<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setting extends Model
{
    
    protected $fillable = [
        'key',
        "value",
        "type",
        "attributes",
        "group"
    ];
    protected $casts = [
        "attributes" => "array",
    ];

    public function tenantSettings(): HasMany
    {
        return $this->hasMany(TenantSetting::class);
    }
}
