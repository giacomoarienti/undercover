<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    private array $colors = [
        ['name' => 'Red', 'rgb' => '#ff0000'],
        ['name' => 'Blue', 'rgb' => '#0000ff'],
        ['name' => 'Green', 'rgb' => '#00ff00'],
        ['name' => 'Yellow', 'rgb' => '#ffff00'],
        ['name' => 'Purple', 'rgb' => '#800080'],
        ['name' => 'Orange', 'rgb' => '#ffa500'],
        ['name' => 'Pink', 'rgb' => '#ffc0cb'],
        ['name' => 'Brown', 'rgb' => '#a52a2a'],
        ['name' => 'Black', 'rgb' => '#000000'],
        ['name' => 'White', 'rgb' => '#ffffff'],
    ];

    public function run(): void
    {
        foreach ($this->colors as $color) {
            Color::create($color);
        }
    }
}
