<?php

namespace App\Filament\Resources\Quality\RiskAssessment\RiskResource\Pages;

use App\Filament\Resources\Quality\RiskAssessment\RiskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRisk extends ViewRecord
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
