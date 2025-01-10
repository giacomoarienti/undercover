<?php

namespace Database\Factories;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use Illuminate\Support\Str;

/**
 * @extends Factory<Phone>
 */
class PhoneFactory extends Factory
{
    private array $phoneNames = [
        "Apple" => "iPhone",
        "Samsung" => "Galaxy",
        "Nokia" => "Nokia",
        "Huawei" => "P",
    ];

    private array $descriptiveWords = ['Lite', 'Pro', 'Max', 'Ultra'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand = Brand::inRandomOrder()->first();

        // Random descriptive word and number
        $phone = array_key_exists($brand->name, $this->phoneNames) ?
            $this->phoneNames[$brand->name] : "Phone";
        $description = fake()->randomElement($this->descriptiveWords);
        $number = fake()->numberBetween(1, 15);

        // Construct the phone name
        $name = "{$phone} {$number} {$description}";
        $slug = Str::slug($name);

        // Ensure the name is unique in the database
        while (Phone::where('slug', $slug)->exists()) {
            $description = fake()->randomElement($this->descriptiveWords);
            $number = fake()->numberBetween(1, 15);

            $name = "{$phone} {$number} {$description}";
            $slug = Str::slug($name);
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'brand_id' => $brand->id, // Associate the phone with the selected brand
        ];
    }
}
