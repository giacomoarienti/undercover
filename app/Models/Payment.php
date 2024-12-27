<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        "transaction_id",
    ];

    public function status() : hasOne
    {
        return $this->hasOne(PaymentStatus::class);
    }

    //todo: collegamento con ordine e totale
}
