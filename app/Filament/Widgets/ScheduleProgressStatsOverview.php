<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Schedule;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ScheduleProgressStatsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;
    protected static ?int $sort = 14;

    public ?array $filters = null;
    public ?int $scheduleId = null;
    public ?string $scheduleName = null;

    protected function getHeading(): ?string
    {
        if ($this->scheduleName) {
            return $this->scheduleName;
        }

        if ($this->scheduleId) {
            $teamId = Filament::getTenant()?->id;

            return Schedule::query()
                ->where('id', $this->scheduleId)
                ->when($teamId, fn ($query) => $query->where('team_id', $teamId))
                ->value('name');
        }

        return 'Cronograma';
    }

    protected function getStats(): array
    {
        $teamId = Filament::getTenant()?->id;

        if (! $teamId || ! $this->scheduleId) {
            return [
                Stat::make('Cumplimiento a la fecha', 'N/A'),
                Stat::make('Avance del cronograma', 'N/A'),
            ];
        }

        $scheduleExists = Schedule::query()
            ->where('team_id', $teamId)
            ->where('id', $this->scheduleId)
            ->exists();

        if (! $scheduleExists) {
            return [
                Stat::make('Cumplimiento a la fecha', 'N/A')
                    ->description('Cronograma no encontrado'),
            ];
        }

        $today = now()->startOfDay();

        $eventsQuery = Event::query()
            ->where('team_id', $teamId)
            ->where('schedule_id', $this->scheduleId);

        $totalEvents = (clone $eventsQuery)->count();
        $doneEvents = (clone $eventsQuery)->where('done', true)->count();

        $overdueQuery = (clone $eventsQuery)->whereDate('end_date', '<', $today);
        $overdueTotal = $overdueQuery->count();
        $overdueDone = (clone $overdueQuery)->where('done', true)->count();

        $compliancePercent = $overdueTotal > 0
            ? round(($overdueDone / $overdueTotal) * 100, 1)
            : null;

        $progressPercent = $totalEvents > 0
            ? round(($doneEvents / $totalEvents) * 100, 1)
            : null;

        $complianceValue = $compliancePercent !== null
            ? $compliancePercent . '%'
            : 'N/A';

        $progressValue = $progressPercent !== null
            ? $progressPercent . '%'
            : 'N/A';

        return [
            Stat::make('Cumplimiento a la fecha', $complianceValue)
                ->description('Vencidos: ' . $overdueDone . '/' . $overdueTotal)
                ->color($this->colorForPercent($compliancePercent)),
            Stat::make('Avance del cronograma', $progressValue)
                ->description('Cumplidos: ' . $doneEvents . '/' . $totalEvents)
                ->color($this->colorForPercent($progressPercent)),
        ];
    }

    protected function colorForPercent(?float $percent): ?string
    {
        if ($percent === null) {
            return null;
        }

        if ($percent >= 80) {
            return 'success';
        }

        if ($percent >= 50) {
            return 'warning';
        }

        return 'danger';
    }
}
