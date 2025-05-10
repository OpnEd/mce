<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;

class SetTeamInSession
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Supongamos que tomas el primer equipo del usuario
        $team = $event->user->teams()->first(); // o como se relacione en tu sistema

        if ($team) {
            session(['team_id' => $team->id]);
        }
    }
}
