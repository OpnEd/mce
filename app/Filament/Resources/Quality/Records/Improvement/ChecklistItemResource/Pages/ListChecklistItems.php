<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistItems extends ListRecords
{
    protected static string $resource = ChecklistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
