<?php

namespace App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource\Pages;

use App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateClientSatisfactionEvaluation extends CreateRecord
{
    protected static string $resource = ClientSatisfactionEvaluationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $tenant = Filament::getTenant();
        $data['team_id'] = $tenant?->id ?? auth()->user()?->team_id;

        return $data;
    }
}
