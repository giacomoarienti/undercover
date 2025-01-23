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
            'is_seller' => false,
            'password' => 'password'
        ]);

        User::factory()->create([
            'name' => 'Test Client B',
            'email' => 'testclientb@example.com',
            'is_seller' => false,
            'password' => 'password'
        ]);

        User::factory()->create([
            'name' => 'Test Vendor',
            'email' => 'testvendor@example.com',
            'is_seller' => true,
            'password' => 'password'
        ]);

        User::factory()->create([
            'name' => 'Test Vendor B',
            'email' => 'testvendorb@example.com',
            'is_seller' => true,
            'password' => 'password'
        ]);
    }
}
