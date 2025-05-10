<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoleType: string implements HasLabel
{
    case SUPERADMIN     = 'super-admin';
    case ADMIN          = 'admin';
    case DIRECTOR       = 'director';
    case MEDICO         = 'medico';
    case CLIENTE        = 'cliente';
    case COMERCIAL      = 'comercial';
    case AUXILIARVET    = 'auxiliar-vet';
    case AUXILIARBDG    = 'auxiliar-bodega';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUPERADMIN     => 'Súper Administrador',
            self::ADMIN          => 'Administrador',
            self::DIRECTOR       => 'Director',
            self::MEDICO         => 'Médico',
            self::CLIENTE        => 'Cliente',
            self::COMERCIAL      => 'Asersor Comercial',
            self::AUXILIARVET    => 'Auxiliar Veterinario',
            self::AUXILIARBDG    => 'Auxiliar de Bodega',
        };
    }

    // Opcional: método para obtener todos los valores
    /* public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    } */
    // dentro de App\Enums\RoleType
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
