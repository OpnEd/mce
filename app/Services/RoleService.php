<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Enums\RoleType;
use App\Enums\PermissionType;

class RoleService
{
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles(int $teamId)
    {
        return $this->roleRepository->allRoles($teamId);
    }

    public function getAllPermissions(int $teamId)
    {
        return $this->roleRepository->allPermissions($teamId);
    }

    public function createRole(RoleType $roleType, int $teamId)
    {
        return $this->roleRepository->createRole($roleType, $teamId);
    }

    public function createPermission(PermissionType $permissionType, int $teamId)
    {
        return $this->roleRepository->createPermission($permissionType, $teamId);
    }

    public function assignPermissionToRole(int $roleId, int $permissionId, int $teamId)
    {
        return $this->roleRepository->assignPermissionToRole($roleId, $permissionId, $teamId);
    }

    public function assignRoleToUser(int $userId, int $roleId, int $teamId)
    {
        return $this->roleRepository->assignRoleToUser($userId, $roleId, $teamId);
    }
}
