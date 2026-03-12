<?php

namespace Database\Seeders;

use App\Models\Quality\QualityGoal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualityGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quality_goals = config('quality_goals');
        
        foreach ($quality_goals as $goal) {

            QualityGoal::updateOrCreate(
                [
                    'name' => $goal['name'],
                ],
                [
                    'process_id' => $goal['process_id'],
                    'description' => $goal['description'],
                ]
            );
        }

    }
}
