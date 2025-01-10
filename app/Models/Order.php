<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{

    protected $fillable = ["discount"];

    //TODO: attributi derivati
    public function status() : Attribute {
        return Attribute::make(
            get: fn() => null
        );
    }

    public function completed() : Attribute {
        return Attribute::make(
            get: fn() => null
        );
    }

    public function total() : Attribute {
        return Attribute::make(
            get: fn() => null
        );
    }

    public function coupon() : BelongsTo {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * User is the buyer
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function payment() : BelongsTo {
        return $this->belongsTo(Payment::class);
    }

    public function shipping() : BelongsTo {
        return $this->belongsTo(Shipping::class);
    }

    public function specificProducts() : BelongsToMany {
        return $this->belongsToMany(SpecificProduct::class, "order_specific_products")->withPivot("quantity");
    }

}
