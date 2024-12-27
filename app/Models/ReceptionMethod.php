<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
