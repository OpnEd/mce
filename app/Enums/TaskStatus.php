<?php  // app/Enums/TaskStatus.php

namespace App\Enums;

enum TaskStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    
    // Opcional: método para labels
    public function label(): string
    {
        return match($this) {
            self::InProgress => 'En Progreso',
            self::Completed => 'Completado',
        };
    }
}