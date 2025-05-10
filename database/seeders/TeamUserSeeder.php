<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos el equipo y el usuario con ID 1
        $team = Team::find(2);
        $user = User::find(3);

        if ($team && $user) {
            // Con el método attach se inserta un registro en la tabla pivote
            $team->users()->attach($user->id);

            // Alternativamente, para evitar duplicados podrías usar:
            // $team->users()->syncWithoutDetaching([$user->id]);
        } else {
            $this->command->error('Team o User con ID 1 no existe.');
        }
    }
}
