<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use function Illuminate\Events\queueable;

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
 * @property int $payment_method_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethodId($value)
 * @property-read \App\Models\PaymentStatus $paymentStatus
 * @mixin \Eloquent
 */
class Payment extends Model
{

    protected static function booted() : void
    {
        static::creating(function ($payment) {
            $payment->transaction_id = Payment::generateTransactionId();
            $payment->payment_status_id = PaymentStatus::where(['name' => 'Pending'])->first()->id;
        });

        static::updated(queueable(function ($payment) {
            $payment->order->user->sendNotification("Payment updated", "The payment for your order with date " . $payment->order->created_at->format('d/m/Y H:i') . " has been updated. It is now " . Str::lower($payment->paymentStatus->name) . ".");
        }));
    }

    private static function getRandomHex($num_bytes=4) : string {
        return bin2hex(openssl_random_pseudo_bytes($num_bytes));
    }

    private static function generateTransactionId() : string {
        do {
            $transaction_id = Payment::getRandomHex();
        } while(Payment::where("transaction_id", $transaction_id)->exists());
        return $transaction_id;
    }

    protected $fillable = [
        "payment_method_id"
    ];

    protected $with = [
        "paymentMethod"
    ];

    public function paymentStatus() : BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    public function order() : HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function total() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->order->total
        );
    }

    public function paymentMethod() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
