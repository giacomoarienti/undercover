<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrandSeeder::class,
            PhoneSeeder::class,
            ColorSeeder::class,
            MaterialsSeeder::class,
            PaymentStatusSeeder::class,
            ShippingStatusSeeder::class,
        ]);
    }
}
