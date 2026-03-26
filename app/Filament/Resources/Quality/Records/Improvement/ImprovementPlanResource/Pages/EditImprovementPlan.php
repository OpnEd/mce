<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementPlan extends EditRecord
{
    protected static string $resource = ImprovementPlanResource::class;

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
