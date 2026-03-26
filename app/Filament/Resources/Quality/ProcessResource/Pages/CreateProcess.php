<?php

namespace App\Filament\Resources\Quality\ProcessResource\Pages;

use App\Filament\Resources\Quality\ProcessResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateProcess extends CreateRecord
{
    protected static string $resource = ProcessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = Filament::getTenant()?->id;
        return $data;
    }
}
