<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChecklistItem extends ViewRecord
{
    protected static string $resource = ChecklistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
