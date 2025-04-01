<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'code',
        'sanitary_registry_id',
        'manufacturing_date',
        'expiration_date',
        'data',
    ];

    protected $casts = [
        'manufacturing_date' => 'datetime',
        'expiration_date' => 'datetime',
        'data' => 'array',
    ];

    public function sanitaryRegistry()
    {
        return $this->belongsTo(SanitaryRegistry::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function productRecepcionItems(): HasMany
    {
        return $this->hasMany(ProductReceptionItem::class);
    }
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
