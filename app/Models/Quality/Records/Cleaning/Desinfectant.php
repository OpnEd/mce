<?php

namespace App\Models\Quality\Records\Cleaning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quality\Records\Cleaning\StablishmentArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Desinfectant extends Model
{
    use HasFactory;

    protected $table = 'desinfectants';

    protected $fillable = [
        'team_id',
        'name',
        'active_ingredient',
        'concentration',
        'indications',
        'level',
        'applicable_areas',
        'active',
    ];

    protected $casts = [
        'applicable_areas' => 'array',
        'activa' => 'boolean',
    ];

    public static function getNiveles()
    {
        return [
            'alto' => 'Alto Nivel',
            'intermedio' => 'Nivel Intermedio',
            'bajo' => 'Bajo Nivel',
        ];
    }

    public function getNivelLabelAttribute()
    {
        return self::getNiveles()[$this->nivel] ?? $this->nivel;
    }

    public function getApplicableAreasAttribute()
    {
        if (!$this->applicable_areas) {
            return collect();
        }
        
        return StablishmentArea::whereIn('id', $this->applicable_areas)->get();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}