<?php

namespace App\Filament\Resources\Quality\RiskAssessment\RiskResource\Pages;

use App\Filament\Resources\Quality\RiskAssessment\RiskResource;
use App\Models\Process;
use App\Models\Quality\RiskAssessment\Risk;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;

class RiskMatrix extends Page
{
    protected static string $resource = RiskResource::class;

    protected static string $view = 'filament.pages.risk-matrix';

    protected static ?string $title = 'Matriz de riesgos';

    public ?int $processId = null;

    public function mount(): void
    {
        $this->processId = request()->integer('process_id') ?: null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('volver')
                ->label('Volver a lista')
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => RiskResource::getUrl('index'))
                ->color('gray'),
            Action::make('descargarPdf')
                ->label('Descargar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(function (): string {
                    $tenant = Filament::getTenant();
                    $params = ['tenant' => $tenant?->id];
                    if ($this->processId) {
                        $params['process_id'] = $this->processId;
                    }
                    return route('risk.matrix.pdf', $params);
                })
                ->openUrlInNewTab(),
        ];
    }

    protected function getViewData(): array
    {
        $tenantId = Filament::getTenant()?->id;

        $risks = Risk::query()
            ->with('process')
            ->when($tenantId, fn ($query) => $query->where('team_id', $tenantId))
            ->when($this->processId, fn ($query) => $query->where('process_id', $this->processId))
            ->orderBy('process_id')
            ->orderByDesc('risk_score')
            ->get();

        $processes = Process::query()
            ->when($tenantId, fn ($query) => $query->where('team_id', $tenantId))
            ->orderBy('name')
            ->get(['id', 'name']);

        return [
            'risks' => $risks,
            'processes' => $processes,
            'processId' => $this->processId,
        ];
    }
}
