<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property float $discount
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property int $user_id
 * @property-read Attribute $is_active
 * @property-read mixed $percentage_discount
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUserId($value)
 * @property-read mixed $is_active_attribute
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    protected $fillable = [
        "code",
        "discount",
        "starts_at",
        "expires_at",
        "user_id"
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'discount' => 'float'
    ];

    /**
     * Get if the Coupon is active
     */
    protected function isActiveAttribute(): Attribute
    {
        // if expires_at is < now and starts_at is > now
        return Attribute::make(
            get: fn () => $this->attributes['expires_at'] < now() &&
                $this->attributes["starts_at"] > now()
        );
    }

    /**
     * Get the discount percentage. (ex. 50%)
     */
    protected function percentageDiscount(): Attribute
    {
        return Attribute::make(
            get: fn() => ((string) ($this->attributes['discount'] * 100)) . '%'
        );
    }

    /**
     * Get the Vendor associated to Coupon.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
