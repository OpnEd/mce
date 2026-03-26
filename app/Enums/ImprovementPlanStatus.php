<?php

namespace App\Enums;

enum ImprovementPlanStatus: string
{
    case Pending = 'pendiente';
    case InProgressOnTime = 'en_progreso_al_dia';
    case InProgressLate = 'en_progreso_con_retraso';
    case InReview = 'en_verificacion';
    case Completed = 'completado';
    case Canceled = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::InProgressOnTime => 'En progreso al dia',
            self::InProgressLate => 'En progreso con retraso',
            self::InReview => 'En verificacion',
            self::Completed => 'Completado',
            self::Canceled => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'danger',
            self::InProgressOnTime => 'info',
            self::InProgressLate => 'warning',
            self::InReview => 'primary',
            self::Completed => 'success',
            self::Canceled => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }
}
