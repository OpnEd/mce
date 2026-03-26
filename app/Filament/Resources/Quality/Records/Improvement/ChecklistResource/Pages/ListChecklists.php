<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklists extends ListRecords
{
    protected static string $resource = ChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
