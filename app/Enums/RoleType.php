<?php

namespace App\Enums;

enum RoleType: string
{
    case SUPERADMIN     = 'super-admin';
    case ADMIN          = 'admin';
    case DIRECTOR       = 'director';
    case MEDICO         = 'medico';
    case CLIENTE        = 'cliente';
    case COMERCIAL      = 'comercial';
    case AUXILIARVET    = 'auxiliar-vet';
    case AUXILIARBDG    = 'auxiliar-bodega';

    // Opcional: método para obtener todos los valores
    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
