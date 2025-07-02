<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    /** @use HasFactory<\Database\Factories\PetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'name',
        'species', // e.g., 'dog', 'cat', etc.
        'gender', //
        'birth_date',
        'weight',
        'history', // medical history
        'is_alive',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'history' => 'array', // Store medical history as an array
        'is_alive' => 'boolean', // Store alive status as a boolean
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function anesthesiaSheets(): HasMany
    {
        return $this->hasMany(AnesthesiaSheet::class);
    }
}
