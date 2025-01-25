<?php

namespace Database\Seeders;

use App\Models\ShippingStatus;
use Illuminate\Database\Seeder;

class ShippingStatusSeeder extends Seeder
{
    private array $statuses = [
        ['name' => 'Completed', 'description' => 'Item received.'],
        ['name' => 'Shipping', 'description' => 'The item is being shipped.'],
        ['name' => 'Warehouse', 'description' => 'The item is in the warehouse.']
    ];

    public function run(): void
    {
        foreach ($this->statuses as $status) {
            ShippingStatus::create($status);
        }
    }
}
