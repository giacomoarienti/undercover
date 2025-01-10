<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test Client',
            'email' => 'testclient@example.com',
            'password' => 'password'
        ]);

        User::factory()->create([
            'name' => 'Test Vendor',
            'email' => 'testvendor@example.com',
            'is_seller' => true,
            'password' => 'password'
        ]);

        Brand::factory(4)->create();

        Phone::factory(20)->create();
    }
}
