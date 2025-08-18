<?php

namespace App\Filament\Resources\Quality\ScheduleResource\Pages;

use App\Filament\Resources\Quality\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
