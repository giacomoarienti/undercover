<?php

namespace App\Models;

use App\Exceptions\UnavailableProductException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Log;
use function Illuminate\Events\queueable;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $discount
 * @property int $user_id
 * @property int|null $coupon_id
 * @property int|null $payment_id
 * @property int|null $shipping_id
 * @property-read mixed $completed
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\Shipping|null $shipping
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpecificProduct> $specificProducts
 * @property-read int|null $specific_products_count
 * @property-read mixed $status
 * @property-read mixed $total
 * @property-read mixed $total_before_discount
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected static function booted(): void
    {
        static::created(queueable(function (Order $order) {
            $order->sellers()->each(
                function($seller) use ($order) {
                    $seller->sendNotification(
                        "New order", "A new order has been placed for your product(s): " .
                        $order->specificProducts
                            ->filter(fn($specificProduct) => $specificProduct->product->user->id == $seller->id)
                            ->map(fn($specificProduct) => $specificProduct->product->name)
                            ->implode(", "));
                }
            );
        }));
    }

    protected $fillable = [
        'user_id',
        'coupon_id',
        'payment_id',
        'shipping_id',
    ];

    protected $appends = [
        'total_before_discount',
        'discount',
        'total',
    ];

    protected $with = [
        'coupon',
        'specificProducts',
    ];

    public function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->payment->paymentStatus->id != PaymentStatus::where('name', 'Completed')->first()->id) {
                    return OrderStatus::AWAITING_PAYMENT;
                } elseif ($this->shipping == null) {
                    return OrderStatus::PENDING;
                } elseif ($this->shipping->shippingStatus->id == ShippingStatus::where('name', 'Completed')->first()->id) {
                    return OrderStatus::DELIVERED;
                } else {
                    return OrderStatus::SHIPPED;
                }
            }
        );
    }

    public static function place(User $user, Payment $payment, ?Coupon $coupon): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon?->id,
            'payment_id' => $payment->id,
        ]);
        foreach ($user->cart as $item) {
            SpecificProduct::find($item->id)->buy($item->pivot->quantity);
            OrderSpecificProduct::create([
                'order_id' => $order->id,
                'specific_product_id' => $item->id,
                'quantity' => $item->pivot->quantity,
            ]);
        }
        $user->emptyCart();
        return $order;
    }

    public function completed(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status() == OrderStatus::DELIVERED
        );
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->total_before_discount - $this->discount
        );
    }

    public function vendorTotalBeforeDiscount(User $vendor)
    {
        return $this->specificProducts->sum(fn($specificProduct) => $specificProduct->product->user_id == $vendor->id ?
            $specificProduct->pivot->quantity * $specificProduct->product->price : 0);
    }

    public function vendorDiscount(User $vendor)
    {
        return ($this->coupon and ($this->coupon->user_id == $vendor->id)) ?
            $this->vendorTotalBeforeDiscount($vendor) * $this->coupon->discount : 0;
    }

    public function vendorTotal(User $vendor)
    {
        return $this->vendorTotalBeforeDiscount($vendor) - $this->vendorDiscount($vendor);
    }

    public function sellerSpecificProducts(User $seller)
    {
        return $this->specificProducts->where(function ($specificProduct) use ($seller) {
            return $specificProduct->product->user_id == $seller->id;
        })->all();
    }

    public function discount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->coupon == null ? 0 : $this->specificProducts->sum(
                fn($specificProduct) => $specificProduct->product->user_id == $this->coupon->user_id ?
                    $specificProduct->pivot->quantity * $specificProduct->product->price * $this->coupon->discount : 0
            )
        );
    }

    public function totalBeforeDiscount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->specificProducts->sum(fn($specificProduct) => $specificProduct->pivot->quantity * $specificProduct->product->price)
        );
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * User is the buyer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }

    public function specificProducts(): BelongsToMany
    {
        return $this->belongsToMany(SpecificProduct::class, "order_specific_products")->withPivot("quantity")->withTrashed();
    }

    public function sellers(): Collection
    {
        return $this->specificProducts->map(fn($specificProduct) => $specificProduct->product->user)->unique('id');
    }
}
