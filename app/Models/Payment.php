<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $transaction_id
 * @property int $payment_status_id
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\PaymentStatus|null $status
 * @property-read mixed $total
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    //TODO manage transaction ids

    protected $fillable = [
        "transaction_id",
    ];

    public function status() : HasOne
    {
        return $this->hasOne(PaymentStatus::class);
    }

    public function order() : HasOne {
        return $this->hasOne(Order::class);
    }

    public function total() : Attribute
    {
        return Attribute::make(
            get: fn() => null
        );
    }

    //todo: collegamento con ordine e totale
}
