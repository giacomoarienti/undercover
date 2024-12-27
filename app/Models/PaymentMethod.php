<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        "type",
        "number",
        "expiration_date",
        "cvv",
        "email"
    ];
}
