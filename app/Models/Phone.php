<?php

namespace App\Models;

use Database\Factories\PhoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phone extends Model
{
    /** @use HasFactory<PhoneFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "brand_id"
    ];

    /**
     * Get the Brand that produces this Phone model.
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Returns the Products available for this Phone model.
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
