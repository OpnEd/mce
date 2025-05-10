<?php

namespace App\Filament\Pages;

use App\Models\Permission;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public $team;
    public $user;
    public $hasPermission;
    public $roles;

    public function mount()
    {
        $this->team = Filament::getTenant();
        $this->user = auth()->user();
        /* $this->hasPermission = $this->user->permissions(); */
        //->where('model_has_permissions.team_id', $this->team->id);
        /* $this->roles = $this->user->hasPermissionTo('confirm-purchase')
            ->where('permissions.team_id', $this->team->id); */
        /* $this->hasPermission = $this->user->hasPermissionTo('confirm-purchase', $this->team); */
        $teamId = $this->team->id;
        $this->hasPermission = $this->user->can('view-purchase')
        && ($this->team && $this->user->teams()->where('teams.id', $this->team->id)->exists());
        /* $this->hasPermission = $this->user->roles()
            ->whereHas('permissions', function ($query) use ($teamId) {
                $query->where('name', 'view-purchase')
                    ->where('team_id', $teamId);
            })
            ->exists(); */
    }

    protected static string $view = 'filament.pages.settings';
}
