<?php

namespace App\Filament\Pos\Resources\CustomerResource\Pages;

use App\Filament\Pos\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
