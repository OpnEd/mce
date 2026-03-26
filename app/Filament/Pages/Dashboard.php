<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ScheduleProgressStatsOverview;
use App\Models\Schedule;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;
    
    public $user;

    public function getColumns(): int | string | array
    {
        return 3;
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->form([
                    DatePicker::make('startDate')
                        ->label('Desde'),
                    DatePicker::make('endDate')
                        ->label('Hasta'),
                    // ...
                ]),
            Actions\CreateAction::make()
                ->label('Configurar indicadores')
                ->url(fn (): string => route('filament.admin.resources.indicadores-de-gestion.index', ['tenant' => Filament::getTenant()->id]))
                ->openUrlInNewTab(),
        ];
    }
     public function mount(): void
    {
        $this->user = auth()->user();
    }
    
    public function getHeading(): string
    {
        return __('pages.management_indicators');
    }

    public function getSubheading(): ?string
    {
        return __('En este sitio encuentras los indicadores de gestión de cada uno de los procesos misionales.');
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            //RationalUsePromotionChart::class,
        ];
    }

    public function getWidgets(): array
    {
        $widgets = Filament::getWidgets();
        $teamId = Filament::getTenant()?->id;

        if (! $teamId) {
            return $widgets;
        }

        $schedules = Schedule::query()
            ->where('team_id', $teamId)
            ->orderBy('name')
            ->get(['id', 'name']);

        foreach ($schedules as $schedule) {
            $widgets[] = ScheduleProgressStatsOverview::make([
                'scheduleId' => $schedule->id,
                'scheduleName' => $schedule->name,
            ]);
        }

        return $widgets;
    }
}
