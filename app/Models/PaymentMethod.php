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
 * @property string|null $card_number
 * @property string|null $card_expiration_date
 * @property string|null $card_cvv
 * @property string|null $paypal_email
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCardCvv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCardExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod wherePaypalEmail($value)
 * @property-read mixed $is_default
 * @property-read mixed $default
 * @mixin \Eloquent
 */
class PaymentMethod extends Model
{
    protected $fillable = [
        "type",
        "card_number",
        "card_expiration_date",
        "card_cvv",
        "paypal_email",
        'user_id'
    ];

    protected function cardNumber(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "**** **** **** " . substr($value, -4)
        );
    }

    /**
     * True if the User has set the PaymentMethod as default
     * @return Attribute
     */
    protected function default(): Attribute
    {
        return new Attribute(
            get: fn() => $this->user->defaultPaymentMethod?->id === $this->id
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
