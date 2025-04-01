<?php

namespace App\Filament\TenantManager\Resources\TeamResource\Pages;

use App\Filament\TenantManager\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;
}
