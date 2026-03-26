<?php

namespace App\Filament\Resources\Quality\Records\Improvement\TaskResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
