<?php

namespace App\Services;

use App\Models\ManagementIndicator;
use App\Models\ProductReception;
use App\Models\Purchase;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class IndicatorService
{
    /**
     * Retorna una colección con 12 elementos:
     * [
     *   ['month' => 'Enero',   'percentage' => 72.34],
     *   ...
     * ]
     */
    public function getMonthlyCompliance(int $teamId, ?string $indicatorName = null): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();

        // 1) Carga el team junto con los indicadores y, anidado, su qualityGoal
        $team = Team::with([
            'managementIndicators.qualityGoal',
        ])->findOrFail($teamId);

        // 2) Construye la query sobre la relación e inyecta el filtro de nombre
        $indicatorQuery = $team->managementIndicators()
            ->when(
                $indicatorName,
                fn($query) => $query->where('management_indicators.name', $indicatorName)
            );
        // 3) Toma el primer resultado (filtrado) o fallamos
        $indicator = $indicatorQuery->first();

        if (! $indicator) {
            throw new \RuntimeException(
                "Indicador “{$indicatorName}” no encontrado para el team {$teamId}."
            );
        }

        // 4) Extraemos la meta del pivote
        $customGoal = $indicator->pivot->indicator_goal;
        $roleName = $indicator->pivot->role->name;
            $objective = $indicator->objective;
            $description = $indicator->description;
            $periodicity = $indicator->pivot->periodicity;
            $information_source = $indicator->information_source;
            $numerator = $indicator->numerator;
            $denominator_description = $indicator->denominator_description;
            $type = $indicator->type;

        // 4a) Extrae el QualityGoal (relación normal)
        $qualityGoalName = $indicator->qualityGoal->name;

        // 5) Contamos recepciones y órdenes
        $countRecepcion = ProductReception::where('team_id', $teamId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $countOrden = Purchase::where('team_id', $teamId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 6) Calculamos %
        $progress = $countOrden > 0
            ? intval(($countRecepcion / $countOrden) * 100)
            : 0;

        return [
            'goal'     => $customGoal,
            'progress' => $progress,
            'roleName' => $roleName,
            'qualityGoal' => $qualityGoalName,
            'objective' => $objective,
            'description' => $description,
            'periodicity' => $periodicity,
            'information_source' => $information_source,
            'numerator' => $numerator,
            'denominator_description' => $denominator_description,
            'type' => $type,
        ];
    }
}
