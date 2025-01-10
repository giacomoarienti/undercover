<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
