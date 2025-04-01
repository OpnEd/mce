<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'customer_id',
        'user_id',
        'total',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Relación inversa con cliente
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a uno con factura
    public function factura(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // Relación uno a muchos con FacturaItem
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
