<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
 * @mixin \Eloquent
 */
class Shipping extends Model
{
    protected $fillable = [
        "tracking_number",
        "shipping_company"
    ];

    public function status() : HasOne
    {
        return $this->hasOne(ShippingStatus::class);
    }

    public function order() : HasOne
    {
        return $this->hasOne(Order::class);
    }
}
