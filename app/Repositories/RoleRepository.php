<?php

namespace App\Repositories;

use App\Enums\RoleType;
use App\Enums\PermissionType;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RoleRepository
{
    // Obtiene todos los roles de un tenant
    public function allRoles(int $teamId)
    {
        return Role::where('team_id', $teamId)->get();
    }

    // Obtiene todos los permisos de un tenant
    public function allPermissions(int $teamId)
    {
        return Permission::where('team_id', $teamId)->get();
    }

    // Crea un rol usando un enum para el nombre
    public function createRole(RoleType $roleType, int $teamId)
    {
        return Role::create([
            'name'    => $roleType->value,
            'guard_name' => 'web',
            'team_id' => $teamId,
        ]);
    }

    // Crea un permiso usando un enum para el nombre
    public function createPermission(PermissionType $permissionType, int $teamId)
    {
        return Permission::create([
            'name'    => $permissionType->value,
            'guard_name' => 'web',
            'team_id' => $teamId,
        ]);
    }

    // Asigna un permiso a un rol (asegurando que ambos pertenecen al mismo tenant)
    public function assignPermissionToRole(int $roleId, int $permissionId, int $teamId)
    {
        $role = Role::where('team_id', $teamId)->findOrFail($roleId);
        $permission = Permission::where('team_id', $teamId)->findOrFail($permissionId);
        $role->givePermissionTo($permission);
    }

    // Asigna un rol a un usuario (verificando el contexto tenant)
    public function assignRoleToUser(int $userId, int $roleId, int $teamId)
    {
        $user = User::whereHas('currentTeam', function($q) use ($teamId) {
            $q->where('id', $teamId);
        })->findOrFail($userId);
        $role = Role::where('team_id', $teamId)->findOrFail($roleId);
        $user->assignRole($role);
    }
}
