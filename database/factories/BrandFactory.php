<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    private array $brandNames = ['Apple', 'Samsung', 'Nokia', 'Huawei'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement($this->brandNames);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
