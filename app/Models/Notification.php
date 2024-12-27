<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        "read",
        "title",
        "body",
        "user_id"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
