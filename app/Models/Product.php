<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Return the vendor User who sells the Product.
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
     * @return HasMany
     */
    public function reviews() : HasMany
    {
        return $this->hasMany(Review::class, SpecificProduct::class);
    }
}
