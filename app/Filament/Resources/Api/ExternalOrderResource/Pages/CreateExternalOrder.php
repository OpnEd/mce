<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExternalOrder extends CreateRecord
{
    protected static string $resource = ExternalOrderResource::class;
}
