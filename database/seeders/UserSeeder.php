<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
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
    }
}
