<?php

namespace App\Console\Commands;

use App\Models\Quality\Records\Improvement\ImprovementPlan;
use Illuminate\Console\Command;

class MarkOverdueImprovementPlans extends Command
{
    protected $signature = 'quality:improvement-plans:mark-overdue';
    protected $description = 'Marca planes de mejora vencidos como en progreso con retraso';

    public function handle(): int
    {
        $updated = ImprovementPlan::markOverdue();

        $this->info("Planes actualizados: {$updated}");

        return self::SUCCESS;
    }
}
