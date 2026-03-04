<?php

namespace App\Filament\Pages\Tenancy;

use App\Jobs\SetupTeamJob;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nombre de la compañía')->required(),
                TextInput::make('identification')->label('NIT')->required(),
                TextInput::make('address')->label('Dirección')->required(),
                TextInput::make('email')->label('E-mail')->email()->required(),
                TextInput::make('phonenumber')->label('Teléfono (fijo o celular)')->tel()->required(),
            ]);
    }

    /**
     * Maneja todo el proceso de registro en una transacción.
     *
     * @param array $data
     * @return Team
     * @throws \Throwable
     */
    protected function handleRegistration(array $data): Team
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear el equipo
            $team = Team::create($data);

            // 2. Asociar el usuario actual (propietario) al equipo
            $owner = Auth::user();
            $team->users()->syncWithoutDetaching([$owner->id => ['is_owner' => true]]);

            // 3. Despachar el job para la configuración asíncrona
            SetupTeamJob::dispatch($team, $owner);

            return $team;
        });
    }
}
