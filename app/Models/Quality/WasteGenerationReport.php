<?php

namespace App\Models\Quality;

use App\Models\Waste;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteGenerationReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'informes';

    protected $fillable = [
        'numero_informe',
        'anio',
        'descripcion',
        'team_id',
        'user_id',
        'total_reciclable',
        'total_ordinario',
        'total_guardian',
        'total_bolsa_roja',
        'total_general',
        'cantidad_registros',
        'fecha_generacion',
        'estado',
        'resumen',
    ];

    protected $casts = [
        'resumen' => 'json',
        'fecha_generacion' => 'datetime',
        'anio' => 'integer',
        'total_reciclable' => 'float',
        'total_ordinario' => 'float',
        'total_guardian' => 'float',
        'total_bolsa_roja' => 'float',
        'total_general' => 'float',
    ];

    /**
     * Relación: Informe pertenece a un Team
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relación: Informe pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generar número de informe único
     * Formato: INF-2025-001
     */
    public static function generarNumeroInforme(int $anio, int $team_id): string
    {
        $count = static::where('anio', $anio)
            ->where('team_id', $team_id)
            ->count() + 1;

        return sprintf('INF-%d-%03d', $anio, $count);
    }

    /**
     * Calcular totales desde los residuos del año
     * Genera un informe con los datos agregados
     */
    public static function generarInformeDelAnio(int $anio, int $team_id, int $user_id): self
    {
        $residuosDelAnio = Waste::where('team_id', $team_id)
            ->whereYear('created_at', $anio)
            ->get();

        // Calcular totales
        $totalReciclable = $residuosDelAnio->sum('reciclable');
        $totalOrdinario = $residuosDelAnio->sum('ordinario');
        $totalGuardian = $residuosDelAnio->sum('guardian');
        $totalBolsaRoja = $residuosDelAnio->sum('bolsa_roja');
        $totalGeneral = $totalReciclable + $totalOrdinario + $totalGuardian + $totalBolsaRoja;

        // Crear resumen estadístico
        $resumen = [
            'cantidad_registros' => $residuosDelAnio->count(),
            'promedio_reciclable' => $residuosDelAnio->count() > 0 ? $totalReciclable / $residuosDelAnio->count() : 0,
            'promedio_ordinario' => $residuosDelAnio->count() > 0 ? $totalOrdinario / $residuosDelAnio->count() : 0,
            'promedio_guardian' => $residuosDelAnio->count() > 0 ? $totalGuardian / $residuosDelAnio->count() : 0,
            'promedio_bolsa_roja' => $residuosDelAnio->count() > 0 ? $totalBolsaRoja / $residuosDelAnio->count() : 0,
            'fecha_primer_registro' => $residuosDelAnio->min('created_at'),
            'fecha_ultimo_registro' => $residuosDelAnio->max('created_at'),
            'porcentaje_reciclable' => $totalGeneral > 0 ? ($totalReciclable / $totalGeneral) * 100 : 0,
            'porcentaje_ordinario' => $totalGeneral > 0 ? ($totalOrdinario / $totalGeneral) * 100 : 0,
            'porcentaje_guardian' => $totalGeneral > 0 ? ($totalGuardian / $totalGeneral) * 100 : 0,
            'porcentaje_bolsa_roja' => $totalGeneral > 0 ? ($totalBolsaRoja / $totalGeneral) * 100 : 0,
        ];

        // Crear informe
        $informe = static::create([
            'numero_informe' => self::generarNumeroInforme($anio, $team_id),
            'anio' => $anio,
            'team_id' => $team_id,
            'user_id' => $user_id,
            'total_reciclable' => $totalReciclable,
            'total_ordinario' => $totalOrdinario,
            'total_guardian' => $totalGuardian,
            'total_bolsa_roja' => $totalBolsaRoja,
            'total_general' => $totalGeneral,
            'cantidad_registros' => $residuosDelAnio->count(),
            'estado' => 'generado',
            'resumen' => $resumen,
            'descripcion' => "Informe de residuos generado para el año {$anio}",
        ]);

        return $informe;
    }

    /**
     * Obtener el año anterior
     */
    public static function getAnioAnterior(): int
    {
        return now()->subYear()->year;
    }

    /**
     * Scope: filtrar por año
     */
    public function scopePorAnio($query, int $anio)
    {
        return $query->where('anio', $anio);
    }

    /**
     * Scope: filtrar por equipo
     */
    public function scopePorTeam($query, int $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope: filtrar por estado
     */
    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Formatear el nombre del informe para visualización
     */
    public function getNombreFormato(): string
    {
        return "{$this->numero_informe} - {$this->anio}";
    }

    /**
     * Validar que el informe tenga datos
     */
    public function tieneValidez(): bool
    {
        return $this->cantidad_registros > 0 && $this->total_general > 0;
    }
}
