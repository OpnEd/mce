<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Enums\RoleType;
use App\Enums\PermissionType;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creación de permisos a partir de los casos del Enum PermissionType
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'web',
                'team_id' => 1, // Puedes asignar un team_id si es multitenant
                // Puedes incluir otros campos o asignar un team_id si es multitenant
            ]);
        }

        // Creación de roles a partir de los casos del Enum RoleType
        foreach (RoleType::cases() as $role) {
            Role::firstOrCreate([
                'name' => $role->value,
                'guard_name' => 'web',
                'team_id' => 1, // Puedes asignar un team_id si es multitenant
                // Puedes incluir otros campos o asignar un team_id si es multitenant
            ]);
        }
    }
}
