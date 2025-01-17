<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    private array $statuses = [
        ['name' => 'Completed', 'description' => 'Payment received and completed.'],
        ['name' => 'Pending', 'description' => 'Payment received but not completed.'],
        ['name' => 'Cancelled', 'description' => 'Payment cancelled.'],
        ['name' => 'Failed', 'description' => 'Payment failed, insufficient funds or other reasons.'],
    ];

    public function run(): void
    {
        foreach ($this->statuses as $status) {
            PaymentStatus::create($status);
        }
    }
}
