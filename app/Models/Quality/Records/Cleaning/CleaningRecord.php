<?php

namespace App\Models\Quality\Records\Cleaning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CleaningRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cleaning_records';

    protected $fillable = [
        'team_id',
        'user_id',
        'start_time',
        'end_time',
        'shift',
        'shift_notes',
        'cleaned_areas',
        'observations',
        'reviewed_by',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'cleaned_areas' => 'array',
    ];

    public static function getEstados()
    {
        return [
            'pendiente' => 'Pendiente',
            'completado' => 'Completado',
            'observaciones' => 'Con Observaciones',
        ];
    }

    public function getEstadoLabelAttribute()
    {
        return self::getEstados()[$this->status] ?? $this->status;
    }
    public static function getShifts()
    {
        return [
            'mañana' => 'Mañana (6:00 - 14:00)',
            'tarde' => 'Tarde (14:00 - 22:00)',
            'dia_completo' => 'Día Completo',
        ];
    }

    public function getShiftLabelAttribute()
    {
        return self::getShifts()[$this->shift] ?? $this->shift;
    }

    public function getShiftColorAttribute()
    {
        return match ($this->shift) {
            'mañana' => 'primary',
            'tarde' => 'info',
            'dia_completo' => 'success',
            default => 'gray'
        };
    }

    public function getShiftIconAttribute()
    {
        return match ($this->shift) {
            'mañana' => 'heroicon-o-sun',
            'tarde' => 'heroicon-o-cloud-sun',
            'night' => 'heroicon-o-moon',
            'dia_completo' => 'heroicon-o-clock',
            default => 'heroicon-o-question-mark-circle'
        };
    }

    public function getDurationAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->diff($end)->format('%H:%I');
    }

    public function getGeneralStatusAttribute()
    {
        if (empty($this->cleaned_areas)) {
            return 'sin_datos';
        }

        $statuses = collect($this->cleaned_areas)->pluck('status')->filter();

        if ($statuses->contains('en_proceso')) {
            return 'en_proceso';
        } elseif ($statuses->every(fn($status) => $status === 'completada')) {
            return 'completada';
        } else {
            return 'mixto';
        }
    }

    public function getStatusCountsAttribute()
    {
        if (empty($this->cleaned_areas)) {
            return ['total' => 0, 'completadas' => 0, 'en_proceso' => 0];
        }

        $statuses = collect($this->cleaned_areas)->pluck('status');

        return [
            'total' => count($this->cleaned_areas),
            'completadas' => $statuses->where('', 'completada')->count(),
            'en_proceso' => $statuses->where('', 'en_proceso')->count(),
        ];
    }

    // Método para verificar si ya existen registros en el día
    public static function hasRecordsForDate($date)
    {
        return self::whereDate('created_at', $date)->exists();
    }

    // Método para obtener registros del día agrupados por turno
    public static function getRecordsForDateGroupedByShift($date)
    {
        try {
            $records = self::whereDate('created_at', $date)
                ->with('user')
                ->orderBy('shift')
                ->orderBy('start_time')
                ->get();

            return $records->groupBy('shift');
        } catch (\Exception $e) {
            return collect();
        }
    }

    // Relación con las áreas basada en el JSON
    public function getAreasLimpiadasModelsAttribute()
    {
        if (!$this->cleaned_areas) {
            return collect();
        }

        $areaIds = collect($this->cleaned_areas)->pluck('area_id')->toArray();
        return StablishmentArea::whereIn('id', $areaIds)->get();
    }

    // Relación con sustancias basada en el JSON
    public function getSustanciasUtilizadasModelsAttribute()
    {
        if (!$this->substances_used) {
            return collect();
        }

        $sustanciaIds = collect($this->substances_used)->pluck('desinfectant_id')->toArray();
        return Desinfectant::whereIn('id', $sustanciaIds)->get();
    }

    // Relación con implementos basada en el JSON
    public function getImplementosUtilizadosModelsAttribute()
    {
        if (!$this->implements_used) {
            return collect();
        }

        return CleaningImplement::whereIn('id', $this->implements_used)->get();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
