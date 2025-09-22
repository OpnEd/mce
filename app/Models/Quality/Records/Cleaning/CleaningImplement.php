<?php

namespace App\Models\Quality\Records\Cleaning;

use App\Models\Quality\Records\Cleaning\StablishmentArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CleaningImplement extends Model
{
    use SoftDeletes;

    protected $table = 'cleaning_implements';

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'type',
        'areas_use',
        'active',
    ];

    protected $casts = [
        'areas_use' => 'array',
        'activo' => 'boolean',
    ];

    public static function getTypes()
    {
        return [
            'desechable' => 'Desechable',
            'reutilizable' => 'Reutilizable',
        ];
    }

    public function getTipoLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function areasUse()
    {
        if (!$this->areas_use) {
            return collect();
        }
        
        return StablishmentArea::whereIn('id', $this->areas_use)->get();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}