<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipping extends Model
{
    protected $fillable = [
        "tracking_number",
        "shipping_company"
    ];

    public function status() : hasOne
    {
        return $this->hasOne(ShippingStatus::class);
    }
}
