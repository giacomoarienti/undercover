<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\Shipping;
use App\Models\ShippingStatus;
use Illuminate\Console\Command;

class AdvanceOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:advance-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function advance(Order $order){
        if($order->payment->paymentStatus->name === 'Completed'){
            $this->advanceShipping($order);
        }else{
            $this->advancePayment($order);
        }
    }

    private function advanceShipping(Order $order){
        switch ($order->shipping?->shippingStatus?->name) {
            case null:
                $shipping = Shipping::create([
                    "tracking_number" => fake()->regexify('[A-Z]{2}[0-9]{9}[A-Z]{2}'),
                    "shipping_company" => fake()->company(),
                    "shipping_status_id" => ShippingStatus::where('name', 'Warehouse')->first()->id,
                ]);
                $order->shipping()->associate($shipping);
                $order->save();
                break;
            case 'Warehouse':
                $order->shipping->shippingStatus()->associate(ShippingStatus::where('name', 'Completed')->first());
                $order->shipping->save();
                break;
            default:
                break;
        }
    }

    private function advancePayment(Order $order){
        $order->payment->paymentStatus()->associate(PaymentStatus::where('name', 'Completed')->first());
        $order->payment->save();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::each(fn(Order $order) => $this->advance($order));
        $this->info('All orders advanced');
    }
}
