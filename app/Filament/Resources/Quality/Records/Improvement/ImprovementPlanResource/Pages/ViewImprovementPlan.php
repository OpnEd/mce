<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ImprovementPlanResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;

class ViewImprovementPlan extends ViewRecord
{
    protected static string $resource = ImprovementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('descargar_pdf')
                ->label('Descargar PDF')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url(function () {
                    $tenant = Filament::getTenant();
                    return route('improvement.plan.pdf', [
                        'tenant' => $tenant?->id,
                        'plan' => $this->record,
                    ]);
                })
                ->openUrlInNewTab(),
        ];
    }
}
