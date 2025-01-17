<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    private array $brandNames = ['Apple', 'Samsung', 'Nokia', 'Huawei', 'Xiaomi', 'Oppo'];

    public function run(): void
    {
        foreach ($this->brandNames as $brandName) {
            Brand::create(['name' => $brandName, 'slug' => Str::slug($brandName)]);
        }
    }
}
