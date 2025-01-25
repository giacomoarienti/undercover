<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use Illuminate\Support\Str;

class MaterialsSeeder extends Seeder
{
    private array $materials = [
        "Metal",
        "Plastic",
        "Leather",
        "Rubber",
    ];

    public function run(): void
    {
        foreach ($this->materials as $material) {
            Material::create(['name' => $material, 'slug' => Str::slug($material)]);
        }
    }
}
