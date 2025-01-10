<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Payment extends Model
{
    protected $fillable = [
        "transaction_id",
    ];

    public function status() : HasOne
    {
        return $this->hasOne(PaymentStatus::class);
    }

    public function order() : HasOne {
        return $this->hasOne(Order::class);
    }

    public function total() : Attribute
    {
        return Attribute::make(
            get: fn() => null
        );
    }

    //todo: collegamento con ordine e totale
}
