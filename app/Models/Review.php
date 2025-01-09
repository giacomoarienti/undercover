<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        "title",
        "body",
        "stars",
        "specific_product_id",
    ];

    /**
     * Returns the User who has written the Review.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the SpecificProduct that the Review refers to.
     * @return BelongsTo
     */
    public function specificProduct(): BelongsTo
    {
        return $this->belongsTo(SpecificProduct::class);
    }
}
