<?php

namespace App\Filament\Resources\SpillCleanupResource\Pages;

use App\Filament\Resources\SpillCleanupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;

class CreateSpillCleanup extends CreateRecord
{
    protected static string $resource = SpillCleanupResource::class;
    protected static ?string $title = 'Registrar Limpieza de Derrame';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }


}
