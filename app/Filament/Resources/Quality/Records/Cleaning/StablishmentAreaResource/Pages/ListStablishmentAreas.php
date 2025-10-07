<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStablishmentAreas extends ListRecords
{
    protected static string $resource = StablishmentAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
