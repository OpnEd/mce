<?php

namespace App\Services;

use App\Models\ManagementIndicator;
use App\Models\ProductReception;
use App\Models\Purchase;
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
    public function getMonthlyReceptionCompliance($teamId): int
    {

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        /* $indicador = ManagementIndicator::where('team_id', $teamId)
            ->where('name', $indicator)->first();
        $goal = $indicador->indicator_goal; */

        $countRecepcion = ProductReception::where('team_id', $teamId)->whereBetween('created_at', [$startDate, $endDate])->count();
        $countOrden = Purchase::where('team_id', $teamId)->whereBetween('created_at', [$startDate, $endDate])->count();

        $progress = ($countOrden > 0) ? intval(($countRecepcion / $countOrden) * 100) : 0;

        return $progress;
    }
}
