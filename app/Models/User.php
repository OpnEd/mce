<?php

namespace App\Models;

use App\Enums\RoleType;
use App\Traits\MultiTenantHasRoles;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,
        Notifiable,
        HasRoles;
    //MultiTenantHasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data' => 'array',
        ];
    }

    /**
     * Determine if the user can access the given Filament panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        /* $teamId = session('team_id'); // ⚠️ Este es el valor que se usa para filtrar roles

        if (!$teamId) {
            return false; // Si no hay team en sesión, denegamos acceso
        }

        $team = Team::find($teamId); // O el modelo que uses para equipos

        if (! $team) {
            return false;
        }

        if ($panel->getId() === 'admin') {
            //return $this->hasAnyRole(RoleType::values(), $team);
            return $this->hasAnyRole(array_map(fn($case) => $case->value, RoleType::cases()), $team);

        }

        if ($panel->getId() === 'tenantManager') {
            return $this->hasRole(RoleType::SUPERADMIN->value, $team);
        }

        return false; */
        /* $team = Team::find(session('team_id'));

        if (! $team) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => $this->hasAnyRole(RoleType::values(), $team),
            'tenantManager' => $this->hasRole(RoleType::SUPERADMIN->value, $team),
            default => false,
        }; */
        /* $teamId = session('team_id');
        if (! $teamId) {
            return false;
        }

        $team = Team::find($teamId);
        if (! $team) {
            return false;
        } */
        /*  $team = 1;
        return match ($panel->getId()) {
             'admin' => $this->hasRole([
                'super-admin',
                'admin',
                'director',
                'medico',
                'cliente',
                'comercial',
                'auxiliar-vet',
                'auxiliar-bodega'
            ], $team),
            'tenantManager' => $this->hasRole('super-admin', $team),
            default => false,
        };*/
        return true;
        /* $team = Filament::getTenant(); // Obtener tenant actual desde tu lógica (ej: session, subdominio, etc.)

        return match ($panel->getId()) {
            'admin' => $this->hasAnyRole(
                $team, // ← Team/tenant actual
                [
                    'super-admin',
                    'admin',
                    'director',
                    'medico',
                    'cliente',
                    'comercial',
                    'auxiliar-vet',
                    'auxiliar-bodega'
                ]
            ),
            'tenantManager' => $this->hasRole('super-admin', $team), // ← Team como segundo parámetro
            default => false
        }; */
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relación: Un usuario pertenece a un equipo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id');
    }

    public function user_answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
