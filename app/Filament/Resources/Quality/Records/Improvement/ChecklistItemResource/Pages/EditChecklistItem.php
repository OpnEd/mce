<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistItem extends EditRecord
{
    protected static string $resource = ChecklistItemResource::class;

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
