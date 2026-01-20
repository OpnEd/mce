<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListExternalOrders extends ListRecords
{
    protected static string $resource = ExternalOrderResource::class;


    public function getTabs(): array
    {
        $currentTeamId = Filament::getTenant()->id;

        return [
            'available' => Tab::make('Disponibles')
                ->icon('heroicon-m-check-badge')
                ->badge($this->getAvailableCount())
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('team_id'))
                ->query(fn() => $this->getResource()::getEloquentQuery()->whereNull('team_id')),

            'my_team' => Tab::make('Mis Órdenes')
                ->icon('heroicon-m-check-circle')
                ->badge($this->getMyTeamCount())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('team_id', $currentTeamId))
                ->query(fn() => $this->getResource()::getEloquentQuery()->where('team_id', $currentTeamId)),

            'all' => Tab::make('Todas')
                ->badge($this->getAllCount())
                ->query(fn() => $this->getResource()::getEloquentQuery()),
        ];
    }

    private function getAvailableCount(): int
    {
        return $this->getResource()::getEloquentQuery()->whereNull('team_id')->count();
    }

    private function getMyTeamCount(): int
    {
        $currentTeamId = Filament::getTenant()->id;
        return $this->getResource()::getEloquentQuery()
            ->where('team_id', $currentTeamId)
            ->count();
    }

    private function getAllCount(): int
    {
        return $this->getResource()::getEloquentQuery()->count();
    }
}
