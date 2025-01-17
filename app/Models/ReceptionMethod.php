<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property string|null $iban_number
 * @property string|null $iban_swift
 * @property string|null $iban_holder_name
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereIbanHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereIbanNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereIbanSwift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceptionMethod whereUserId($value)
 * @mixin \Eloquent
 */
class ReceptionMethod extends Model
{
    protected $fillable = [
        'type',
        'iban_number',
        'iban_swift',
        'iban_holder_name',
        'user_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
