<?php

namespace App\Jobs;

use App\Models\Team;
use App\Models\User;
use App\Services\TeamSetupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetupTeamJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Team $team,
        public User $owner
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(TeamSetupService $teamSetupService): void
    {
        Log::info("Iniciando SetupTeamJob para el team: {$this->team->id}");

        try {
            $teamSetupService->setupTeam($this->team, $this->owner);
            Log::info("SetupTeamJob completado exitosamente para el team: {$this->team->id}");
        } catch (\Throwable $e) {
            Log::error("Error en SetupTeamJob para el team: {$this->team->id}", ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}
