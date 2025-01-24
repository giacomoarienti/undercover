<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function Illuminate\Events\queueable;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $tracking_number
 * @property string $shipping_company
 * @property int $shipping_status_id
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\ShippingStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 * @property-read \App\Models\ShippingStatus $shippingStatus
 * @mixin \Eloquent
 */
class Shipping extends Model
{
    protected static function booted(){
        static::created(queueable(function($shipping) {
            Log::info("Shipping updated");
            $shipping->order->user->sendNotification("Shipping updated", "The shipping for your order from " . $shipping->order->created_at->format('d/m/Y H:i') . " has been updated. It is now " . Str::lower($shipping->shippingStatus->name) . ".");
        }));

        static::updated(queueable(function($shipping) {
            Log::info("Shipping updated");
            $shipping->order->user->sendNotification("Shipping updated", "The shipping for your order from " . $shipping->order->created_at->format('d/m/Y H:i') . " has been updated. It is now " . Str::lower($shipping->shippingStatus->name) . ".");
        }));
    }

    protected $fillable = [
        "tracking_number",
        "shipping_company",
        "shipping_status_id"
    ];

    public function shippingStatus() : BelongsTo
    {
        return $this->belongsTo(ShippingStatus::class);
    }

    public function order() : HasOne
    {
        return $this->hasOne(Order::class);
    }
}
