<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoleType: string implements HasLabel
{
    //case SUPERADMIN     = 'super-admin';
    case ADMIN          = 'Administrador';
    case DIRECTOR       = 'Director Técnico';
    case CLIENTE        = 'Cliente';
    case COMERCIAL      = 'Auxiliar de Farmacia';

    public function getLabel(): ?string
    {
        return match ($this) {
            //self::SUPERADMIN     => 'Súper Administrador',
            self::ADMIN          => 'Administrador',
            self::DIRECTOR       => 'Director',
            self::CLIENTE        => 'Cliente',
            self::COMERCIAL      => 'Auxiliar de Farmacia',
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
