<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Enums\RoleType;
use App\Enums\PermissionType;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creación de permisos a partir de los casos del Enum PermissionType
        /* foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'web',
                'team_id' => 2, // Puedes asignar un team_id si es multitenant
                // Puedes incluir otros campos o asignar un team_id si es multitenant
            ]);
        }

        // Creación de roles a partir de los casos del Enum RoleType
        foreach (RoleType::cases() as $role) {
            Role::firstOrCreate([
                'name' => $role->value,
                'guard_name' => 'web',
                'team_id' => 2, // Puedes asignar un team_id si es multitenant
                // Puedes incluir otros campos o asignar un team_id si es multitenant
            ]);
        } */
       // 1) Intentamos leer la opción --teamId, si no existe, fallback a un tenant activo
        $teamId = $this->command->option('teamId')
                  ?: optional(\Filament\Facades\Filament::getTenant())->id;

        if (! $teamId) {
            $this->command->error('No se pudo determinar el team_id.');
            return;
        }

        // 2) Preparamos el entorno de permisos para este team
        app()->make(PermissionRegistrar::class)
             ->setPermissionsTeamId($teamId);

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
            'team_id' => $teamId,
        ]);
        $user = Auth::user()->id;
        $user->assignRole($role);

        $this->command->info("Rol 'admin' asignado a user#{$user->id} en tenant#{$teamId}.");
    }
}
