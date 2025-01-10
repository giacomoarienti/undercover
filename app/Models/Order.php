<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\OrderStatus;
class Order extends Model
{

    protected $fillable = ["discount"];

    public function status() : Attribute {
        return Attribute::make(
            get: function() {
                if($this->payment() == null){
                    return OrderStatus::AWAITING_PAYMENT;
                }elseif($this->shipping() == null){
                    return OrderStatus::PENDING;
                }elseif($this->shipping->status->name == "delivered"){
                    return OrderStatus::DELIVERED;
                }else{
                    return OrderStatus::SHIPPED;
                }
            }
        );
    }

    public function completed() : Attribute {
        return Attribute::make(
            get: fn() => $this->status() == OrderStatus::DELIVERED
        );
    }

    public function total() : Attribute {
        return Attribute::make(
            get: fn() => $this->coupon == null ? $this->totalBeforeDiscount() : $this->totalBeforeDiscount * (1 - $this->coupon->discount)
        );
    }

    public function discount() : Attribute {
        return Attribute::make(
            get: fn() => $this->coupon == null ? 0 : $this->totalBeforeDiscount() * $this->coupon->discount
        );
    }

    public function totalBeforeDiscount() : Attribute {
        return Attribute::make(
            get: fn() => $this->specificProducts->sum(fn($specificProduct) => $specificProduct->pivot->quantity * $specificProduct->price)
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
