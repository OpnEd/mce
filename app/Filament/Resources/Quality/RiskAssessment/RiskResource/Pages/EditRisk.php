<?php

namespace App\Filament\Resources\Quality\RiskAssessment\RiskResource\Pages;

use App\Filament\Resources\Quality\RiskAssessment\RiskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRisk extends EditRecord
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
