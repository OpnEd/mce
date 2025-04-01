<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'identification',
        'address',
        'email',
        'phonenumber',
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    // Relación uno a muchos con Factura
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    // Relación uno a muchos con Venta
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
