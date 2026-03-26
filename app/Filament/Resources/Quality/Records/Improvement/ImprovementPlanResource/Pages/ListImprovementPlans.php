<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImprovementPlans extends ListRecords
{
    protected static string $resource = ImprovementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
