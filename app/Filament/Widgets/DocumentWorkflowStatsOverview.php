<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocumentWorkflowStatsOverview extends BaseWidget
{
    protected static ?int $sort = 12;
    protected ?string $heading = 'Gestión documental';

    public ?array $filters = null;

    protected function getStats(): array
    {
        $teamId = Filament::getTenant()?->id;

        if (! $teamId) {
            return [
                Stat::make('Docs pendientes de revisión', '0'),
                Stat::make('Docs pendientes de aprobación', '0'),
            ];
        }

        $pendingReview = Document::query()
            ->where('team_id', $teamId)
            ->whereNull('reviewed_by')
            ->whereNull('approved_by')
            ->whereNotNull('data->submitted_for_review_at')
            ->count();

        $pendingApproval = Document::query()
            ->where('team_id', $teamId)
            ->whereNull('approved_by')
            ->whereNotNull('reviewed_by')
            ->count();

        return [
            Stat::make('Docs pendientes de revisión', $pendingReview),
            Stat::make('Docs pendientes de aprobación', $pendingApproval),
        ];
    }
}
