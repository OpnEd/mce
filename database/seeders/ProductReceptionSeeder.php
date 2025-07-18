<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\ProductReception;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductReceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Validar existencia de registros base
        if (! Team::where('id', 1)->exists()
            || ! User::where('id', 5)->exists()
            || ! Invoice::where('id', 1)->exists()) {
            $this->command->warn('Faltan Team #1, User #5 o Invoice #1. Abortando ProductReceptionsLastThreeMonthsSeeder.');
            return;
        }

        // Estados posibles
        $statuses = ['in_progress', 'done'];

        // Para cada uno de los últimos tres meses
        for ($monthsAgo = 0; $monthsAgo < 3; $monthsAgo++) {
            $startOfMonth = Carbon::now()->subMonths($monthsAgo)->startOfMonth();
            $endOfMonth   = Carbon::now()->subMonths($monthsAgo)->endOfMonth();

            // Crear exactamente 3 registros
            for ($i = 0; $i < 3; $i++) {
                // Fecha aleatoria dentro del mes
                $randomTimestamp = rand($startOfMonth->timestamp, $endOfMonth->timestamp);
                $receptionDate   = Carbon::createFromTimestamp($randomTimestamp);

                ProductReception::create([
                    'team_id'        => 1,
                    'user_id'        => 5,
                    'purchase_id'    => rand(3, 10),
                    'invoice_id'     => 1,
                    'status'         => fake()->randomElement($statuses),
                    'reception_date' => $receptionDate,
                    'observations'   => fake()->optional()->sentence(),
                    'data'           => null,
                    'created_at'     => $receptionDate,
                    'updated_at'     => $receptionDate,
                ]);
            }

            $this->command->info("3 registros de ProductReception creados para {$startOfMonth->format('Y-m')}.");
        }
    }
}
