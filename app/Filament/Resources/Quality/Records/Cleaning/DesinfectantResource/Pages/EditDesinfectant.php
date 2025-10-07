<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\DesinfectantResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\DesinfectantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesinfectant extends EditRecord
{
    protected static string $resource = DesinfectantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
