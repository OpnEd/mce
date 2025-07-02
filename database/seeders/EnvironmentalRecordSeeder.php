<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvironmentalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $records = [
            [
                'team_id' => 1,
                'user_id' => 1,
                'temp' => 22.50,
                'hum' => 55.20,
            ],
            [
                'team_id' => 1,
                'user_id' => 2,
                'temp' => 23.10,
                'hum' => 53.80,
            ],
            [
                'team_id' => 2,
                'user_id' => 3,
                'temp' => 21.75,
                'hum' => 60.00,
            ],
            [
                'team_id' => 2,
                'user_id' => 4,
                'temp' => 22.00,
                'hum' => 58.10,
            ],
            [
                'team_id' => 3,
                'user_id' => 5,
                'temp' => 24.30,
                'hum' => 52.40,
            ],
            [
                'team_id' => 3,
                'user_id' => 6,
                'temp' => 20.90,
                'hum' => 61.20,
            ],
            [
                'team_id' => 1,
                'user_id' => 7,
                'temp' => 23.50,
                'hum' => 54.00,
            ],
            [
                'team_id' => 2,
                'user_id' => 8,
                'temp' => 22.80,
                'hum' => 57.50,
            ],
            [
                'team_id' => 3,
                'user_id' => 9,
                'temp' => 21.60,
                'hum' => 59.30,
            ],
            [
                'team_id' => 1,
                'user_id' => 10,
                'temp' => 23.20,
                'hum' => 55.10,
            ],
            [
                'team_id' => 2,
                'user_id' => 11,
                'temp' => 22.40,
                'hum' => 56.80,
            ],
            [
                'team_id' => 3,
                'user_id' => 12,
                'temp' => 24.00,
                'hum' => 53.60,
            ],
            [
                'team_id' => 1,
                'user_id' => 13,
                'temp' => 21.95,
                'hum' => 60.50,
            ],
            [
                'team_id' => 2,
                'user_id' => 14,
                'temp' => 22.65,
                'hum' => 57.90,
            ],
            [
                'team_id' => 3,
                'user_id' => 15,
                'temp' => 23.80,
                'hum' => 54.70,
            ],
            [
                'team_id' => 1,
                'user_id' => 16,
                'temp' => 22.10,
                'hum' => 58.30,
            ],
            [
                'team_id' => 2,
                'user_id' => 17,
                'temp' => 21.85,
                'hum' => 59.80,
            ],
            [
                'team_id' => 3,
                'user_id' => 18,
                'temp' => 24.10,
                'hum' => 52.90,
            ],
            [
                'team_id' => 1,
                'user_id' => 19,
                'temp' => 23.00,
                'hum' => 55.60,
            ],
            [
                'team_id' => 2,
                'user_id' => 20,
                'temp' => 22.30,
                'hum' => 57.20,
            ],
            [
                'team_id' => 3,
                'user_id' => 21,
                'temp' => 21.70,
                'hum' => 60.10,
            ],
            [
                'team_id' => 1,
                'user_id' => 22,
                'temp' => 23.40,
                'hum' => 54.20,
            ],
            [
                'team_id' => 2,
                'user_id' => 23,
                'temp' => 22.90,
                'hum' => 56.40,
            ],
        ];

        foreach ($records as $i => &$record) {
            // Distribuir fechas en los últimos 30 días
            $daysAgo = 29 - ($i % 30);
            $date = $now->copy()->subDays($daysAgo);
            $record['created_at'] = $date;
            $record['updated_at'] = $date;
        }
        unset($record);

        \DB::table('environmental_records')->insert($records);
    }
}
