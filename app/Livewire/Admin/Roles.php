<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\RoleService;
use App\Enums\RoleType;

class Roles extends Component
{
    public $roles;
    public $selectedRole; // Este campo puede usarse para seleccionar un rol en formularios
    public $roleType; // Valor del enum como string, desde el formulario

    protected RoleService $roleService;
    protected $teamId;

    public function mount(RoleService $roleService)
    {
        $this->roleService = $roleService;
        // Obtener el tenant actual del usuario autenticado.
        $this->teamId = auth()->user()->currentTeam->id;
        $this->roles = $this->roleService->getAllRoles($this->teamId);
    }

    public function createRole()
    {
        $this->validate([
            'roleType' => 'required|string'
        ]);

        // Se asume que el valor recibido corresponde a uno de los casos del enum.
        $enumRole = RoleType::from($this->roleType);
        $this->roleService->createRole($enumRole, $this->teamId);

        session()->flash('message', 'Rol creado exitosamente.');
        $this->roleType = null;
        $this->roles = $this->roleService->getAllRoles($this->teamId);
    }

    public function render()
    {
        return view('livewire.admin.roles');
    }
}
