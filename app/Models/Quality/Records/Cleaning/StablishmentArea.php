<?php

namespace App\Models\Quality\Records\Cleaning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StablishmentArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stablishment_areas';

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'type',
        'frequency',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function getTypes()
    {
        return [
            'critica' => 'Área Crítica',
            'semicritica' => 'Área Semicrítica',
            'bajo_riesgo' => 'Área de Bajo Riesgo',
        ];
    }

    public static function getFrecuencies()
    {
        return [
            'diaria' => 'Diaria',
            'semanal' => 'Semanal',
            'quincenal' => 'Quincenal',
            'mensual' => 'Mensual',
        ];
    }

    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function getFrequencyLabelAttribute()
    {
        return self::getFrecuencias()[$this->frequency] ?? $this->frequency;
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}