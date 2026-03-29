<?php

namespace App\Filament\Resources\Quality\Training\AssessmentResource\Pages;

use App\Filament\Resources\Quality\Training\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Volver')
                ->url(AssessmentResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
