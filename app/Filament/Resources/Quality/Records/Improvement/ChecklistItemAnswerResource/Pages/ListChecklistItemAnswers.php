<?php

namespace App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource\Pages;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistItemAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecklistItemAnswers extends ListRecords
{
    protected static string $resource = ChecklistItemAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
