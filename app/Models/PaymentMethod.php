<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        "type",
        "card_number",
        "card_expiration_date",
        "card_cvv",
        "paypal_email"
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
