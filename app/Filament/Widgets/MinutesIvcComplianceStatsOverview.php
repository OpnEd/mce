<?php

namespace App\Filament\Widgets;

use App\Models\MinutesIvcSectionEntry;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MinutesIvcComplianceStatsOverview extends BaseWidget
{
    protected static ?int $sort = 13;
    protected ?string $heading = 'Cumplimiento normativo';

    public ?array $filters = null;

    protected function getStats(): array
    {
        $teamId = Filament::getTenant()?->id;

        if (! $teamId) {
            return [
                Stat::make('Cumplimiento IVC', 'N/A'),
                Stat::make('Cumplimiento IVC Crítico', 'N/A'),
            ];
        }

        $baseQuery = MinutesIvcSectionEntry::query()
            ->whereHas('minutesIvcSection', fn ($query) => $query->where('team_id', $teamId));

        $total = (clone $baseQuery)->count();
        $compliant = (clone $baseQuery)->where('compliance', true)->count();
        $criticalCompliant = (clone $baseQuery)
            ->where('compliance', true)
            ->where('criticality', 'Crítico')
            ->count();

        $complianceRate = $total > 0
            ? round(($compliant / $total) * 100, 1) . '%'
            : 'N/A';

        $criticalRate = $total > 0
            ? round(($criticalCompliant / $total) * 100, 1) . '%'
            : 'N/A';

        return [
            Stat::make('Cumplimiento IVC', $complianceRate)
                ->description($compliant . '/' . $total),
            Stat::make('Cumplimiento IVC Crítico', $criticalRate)
                ->description($criticalCompliant . '/' . $total),
        ];
    }
}
