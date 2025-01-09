<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'expires_at' => 'datetime'
    ];

    /**
     * Get if the Coupon is active
     */
    protected function getIsActiveAttribute(): Attribute
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
