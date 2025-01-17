<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Phone;

class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        Phone::factory(20)->create();
    }
}
