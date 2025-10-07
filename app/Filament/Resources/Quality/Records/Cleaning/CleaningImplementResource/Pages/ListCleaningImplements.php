<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCleaningImplements extends ListRecords
{
    protected static string $resource = CleaningImplementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
