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
 * @property string $type
 * @property string|null $number
 * @property string|null $expiration_date
 * @property string|null $cvv
 * @property string|null $email
 * @property int|null $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCvv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUserId($value)
 * @mixin \Eloquent
 */
class PaymentMethod extends Model
{
    protected $fillable = [
        "type",
        "card_number",
        "card_expiration_date",
        "card_cvv",
        "paypal_email"
    ];

    protected function cardNumber(): Attribute {
        return Attribute::make(
            get: fn($value) => "**** **** **** ". substr($value, -4)
        );
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
