<?php

namespace App\Filament\Resources\Quality\Training\CourseResource\Pages;

use App\Filament\Resources\Quality\Training\CourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
