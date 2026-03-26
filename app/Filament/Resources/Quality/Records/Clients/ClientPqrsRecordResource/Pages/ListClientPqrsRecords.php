<?php

namespace App\Filament\Resources\Quality\Records\Clients\ClientPqrsRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Clients\ClientPqrsRecordResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListClientPqrsRecords extends ListRecords
{
    protected static string $resource = ClientPqrsRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar PQRS'),
            Actions\CreateAction::make()
                ->label('Generar QR')
                ->url(fn (): string => route('public.pqrs.qr', ['team' => Filament::getTenant()->id]))
                ->openUrlInNewTab(),
        ];
    }
}
