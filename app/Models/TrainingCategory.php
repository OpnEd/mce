<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @deprecated Modelo legacy de compatibilidad.
 *
 * El dominio activo del modulo de capacitacion vive en App\Models\Quality\Training.
 * No debe usarse para nuevas funcionalidades.
 */
class TrainingCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    public function evaluation_records(): HasMany
    {
        return $this->hasMany(EvaluationRecord::class);
    }
}
