<?php

namespace App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource\Pages;

use App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientSatisfactionEvaluations extends ListRecords
{
    protected static string $resource = ClientSatisfactionEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
