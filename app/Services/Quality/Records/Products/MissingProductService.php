<?php

namespace App\Services\Quality\Records\Products;

use App\Models\ManagementIndicator;
use App\Models\PurchaseItem;
use App\Models\Quality\Records\Products\MissingProduct;
use Carbon\Carbon;
use Filament\Facades\Filament;

class MissingProductService
{
    public function calculateProgress()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $currentTenant = Filament::getTenant();

        $selectionIndicator = ManagementIndicator::where('name', 'Selección')->first();
        $tenantSelectionIndicator = $selectionIndicator->teams->where('id', $currentTenant->id)->first();
        $selectionGoal = $tenantSelectionIndicator->pivot->indicator_goal;


        $aquisitionIndicator = ManagementIndicator::where('name', 'Adquisición')->first();
        $tenantAquisitionIndicator = $aquisitionIndicator->teams->where('id', $currentTenant->id)->first();
        $aquisitionGoal = $tenantAquisitionIndicator->pivot->indicator_goal;

        $selectionCount = PurchaseItem::where('team_id', $currentTenant->id)->where('type', 'faltante_baja_rotacion')->whereBetween('created_at', [$startDate, $endDate])->count();
        $aquisitionCount = PurchaseItem::where('team_id', $currentTenant->id)->where('type', 'faltante_efectivo')->whereBetween('created_at', [$startDate, $endDate])->count();

        $progress = (is_numeric($aquisitionGoal) && $aquisitionGoal > 0)
            ? max(0, min(100, (int) round(($aquisitionCount / $aquisitionGoal) * 100)))
            : 0;

        //$progress = ($goal > 0) ? intval(($count / $goal) * 100) : 100;

        return [
            'aquisitionProgress' => $progress,
            'selectionCount' => $selectionCount,
            'selectionGoal' => $selectionGoal,
            'aquisitionCount' => $aquisitionCount,
            'aquisitionGoal' => $aquisitionGoal,
            'selectionIndicator' => $selectionIndicator,
            'aquisitionIndicator' => $aquisitionIndicator
        ];
    }
}
