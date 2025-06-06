<?php

namespace App\Filament\TenantManager\Resources\UserResource\Pages;

use App\Filament\TenantManager\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
