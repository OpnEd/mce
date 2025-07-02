<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pets = [
            [
                'customer_id' => 2,
                'name' => 'Max',
                'species' => 'dog',
                'gender' => 'male',
                'birth_date' => '2020-05-10',
                'weight' => 20,
                'history' => json_encode(['vaccinated' => true]),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 2,
                'name' => 'Bella',
                'species' => 'cat',
                'gender' => 'female',
                'birth_date' => '2019-08-15',
                'weight' => 5,
                'history' => json_encode(['allergies' => 'none']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'name' => 'Charlie',
                'species' => 'dog',
                'gender' => 'male',
                'birth_date' => '2021-01-20',
                'weight' => 15,
                'history' => json_encode(['notes' => 'friendly']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 4,
                'name' => 'Luna',
                'species' => 'cat',
                'gender' => 'female',
                'birth_date' => '2018-11-30',
                'weight' => 4,
                'history' => json_encode(['spayed' => true]),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 5,
                'name' => 'Rocky',
                'species' => 'dog',
                'gender' => 'male',
                'birth_date' => '2017-07-07',
                'weight' => 25,
                'history' => json_encode(['surgery' => 'knee']),
                'is_alive' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 6,
                'name' => 'Kiwi',
                'species' => 'bird',
                'gender' => 'female',
                'birth_date' => '2022-03-12',
                'weight' => 1,
                'history' => json_encode(['color' => 'green']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 7,
                'name' => 'Spike',
                'species' => 'reptile',
                'gender' => 'male',
                'birth_date' => '2016-09-25',
                'weight' => 2,
                'history' => json_encode(['type' => 'iguana']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 8,
                'name' => 'Milo',
                'species' => 'dog',
                'gender' => 'male',
                'birth_date' => '2020-12-01',
                'weight' => 18,
                'history' => json_encode(['rescued' => true]),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 9,
                'name' => 'Coco',
                'species' => 'bird',
                'gender' => 'female',
                'birth_date' => '2021-06-18',
                'weight' => 1,
                'history' => json_encode(['breed' => 'parrot']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'name' => 'Simba',
                'species' => 'cat',
                'gender' => 'male',
                'birth_date' => '2019-02-14',
                'weight' => 6,
                'history' => json_encode(['favorite_food' => 'fish']),
                'is_alive' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pets')->insert($pets);
    }
}
