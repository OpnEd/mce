<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChecklistItemAnswer extends CreateRecord
{
    protected static string $resource = ChecklistItemAnswerResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
