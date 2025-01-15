<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "name",
        "description",
        "price",
        "material_id",
        "phone_id",
        "user_id"
    ];

    /**
     * Return the Material of the Product.
     * @return BelongsTo
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Return the Phone model compatible with the Product.
     * @return BelongsTo
     */
    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    /**
     * Return the seller User who sells the Product.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
    return $this->belongsTo(User::class);
    }

    /**
     * Return the SpecificProducts (variants) of the Product.
     * @return HasMany
     */
    public function specificProducts(): HasMany
    {
        return $this->hasMany(SpecificProduct::class);
    }

    /**
     * Return the Reviews of the SpecificProducts linked to the Product.
     * @return HasManyThrough
     */
    public function reviews() : HasManyThrough
    {
        return $this->hasManyThrough(Review::class, SpecificProduct::class);
    }
}
