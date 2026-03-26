<?php

namespace App\Filament\Resources\Quality\RiskAssessment\RiskResource\Pages;

use App\Filament\Resources\Quality\RiskAssessment\RiskResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListRisks extends ListRecords
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('verMatriz')
                ->label('Ver matriz')
                ->icon('heroicon-o-table-cells')
                ->url(fn () => RiskResource::getUrl('matrix'))
                ->color('info'),
            Actions\Action::make('descargarPdf')
                ->label('Descargar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(function (): string {
                    $tenant = Filament::getTenant();
                    return route('risk.matrix.pdf', ['tenant' => $tenant?->id]);
                })
                ->openUrlInNewTab(),
        ];
    }
}
