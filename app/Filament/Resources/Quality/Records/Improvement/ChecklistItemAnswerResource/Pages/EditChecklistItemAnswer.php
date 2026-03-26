<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklistItemAnswer extends EditRecord
{
    protected static string $resource = ChecklistItemAnswerResource::class;

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
