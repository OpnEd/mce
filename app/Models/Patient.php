<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    /**
     * Los atributos que se asignarán de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'team_id',
        'customer_id',
        'species',
        'gender',
        'weight',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
        'weight' => 'decimal:2'
    ];

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
