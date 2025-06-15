<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
/**
 * El lote de los productos. Cada team tiene sus propios lotes de productos.
 *
 *
 */
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'code',
        'manufacturer_id',
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

    public function dispatchItems(): HasMany
    {
        return $this->hasMany(DispatchItems::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function product_recepcion_items(): HasMany
    {
        return $this->hasMany(ProductReceptionItem::class);
    }

    public function sanitary_registry(): BelongsTo
    {
        return $this->belongsTo(SanitaryRegistry::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
