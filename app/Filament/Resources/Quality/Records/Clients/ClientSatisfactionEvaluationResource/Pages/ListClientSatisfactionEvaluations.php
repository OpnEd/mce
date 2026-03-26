<?php

namespace App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource\Pages;

use App\Filament\Resources\Quality\Records\Clients\ClientSatisfactionEvaluationResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListClientSatisfactionEvaluations extends ListRecords
{
    protected static string $resource = ClientSatisfactionEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar encuesta de satisfacción'),
            Actions\CreateAction::make()
                ->label('Generar QR')
                ->url(fn (): string => route('public.satisfaction.qr', ['team' => Filament::getTenant()->id]))
                ->openUrlInNewTab(),
        ];
    }
}
