<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use App\Models\SanitaryRegistry;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            TeamSeeder::class,
            TeamUserSeeder::class,
            ManufacturerSeeder::class,
            ProductCategorySeeder::class,
            PharmaceuticalFormSeeder::class,
            ProductSeeder::class,
            SanitaryRegistrySeeder::class,
            BatchSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            DocumentCategorySeeder::class,
            //PurchaseSeeder::class,
            //PurchaseItemSeeder::class,
        ]);
    }
}
