<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {// Aseguramos que exista el team 1 y el supplier 1
        if (! \App\Models\Team::where('id', 1)->exists() ||
            ! \App\Models\Supplier::where('id', 10)->exists()) {
            $this->command->warn('Faltan Team #1 o Supplier #1. Abortando PurchasesLastThreeMonthsSeeder.');
            return;
        }

        // Estados válidos de la migración
        $statuses = ['pending', 'confirmed', 'in progress', 'ready', 'dispatched', 'delivered'];

        // Para cada uno de los últimos tres meses (0 = este mes, 1 = mes anterior, 2 = hace dos meses)
        for ($monthsAgo = 0; $monthsAgo < 3; $monthsAgo++) {
            $startOfMonth = Carbon::now()->subMonths($monthsAgo)->startOfMonth();
            $endOfMonth   = Carbon::now()->subMonths($monthsAgo)->endOfMonth();

            // Generamos entre 2 y 4 compras para ese mes
            $recordsThisMonth = rand(2, 4);

            for ($i = 0; $i < $recordsThisMonth; $i++) {
                // Fecha aleatoria dentro del mes
                $randomDate = Carbon::createFromTimestamp(
                    rand($startOfMonth->timestamp, $endOfMonth->timestamp)
                );

                Purchase::create([
                    'team_id'       => 1,
                    'supplier_id'   => 10,
                    'status'        => fake()->randomElement($statuses),
                    'total'         => fake()->numberBetween(10000, 1000000),
                    'observations'  => fake()->optional()->sentence(),
                    'data'          => null,
                    'created_at'    => $randomDate,
                    'updated_at'    => $randomDate,
                ]);
            }

            $this->command->info("Creada(s) {$recordsThisMonth} purchase(s) para el mes de {$startOfMonth->format('Y-m')}.");
        }
    }
}
