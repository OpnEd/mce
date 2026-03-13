<?php

namespace App\Filament\Resources\Quality\Records\Clients\ClientPqrsRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Clients\ClientPqrsRecordResource;
use App\Models\Quality\Records\Clients\ClientPqrsRecord;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateClientPqrsRecord extends CreateRecord
{
    protected static string $resource = ClientPqrsRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $tenant = Filament::getTenant();
        $data['team_id'] = $tenant?->id ?? auth()->user()?->team_id;

        if (empty($data['response_time_limit_days']) && ! empty($data['type'])) {
            $data['response_time_limit_days'] = ClientPqrsRecord::getDefaultResponseDaysByType($data['type']);
        }

        if (empty($data['response_due_at']) && ! empty($data['received_at']) && ! empty($data['response_time_limit_days'])) {
            $data['response_due_at'] = Carbon::parse($data['received_at'])
                ->addDays((int) $data['response_time_limit_days']);
        }

        return $data;
    }
}
