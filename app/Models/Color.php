<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Color extends Model
{
    protected $fillable = [
        "name",
        "rgb"
    ];

    /**
     * Returns the SpecificProducts available for the Color.
     * @return HasMany
     */
    public function specificProducts(): HasMany
    {
        return $this->hasMany(SpecificProduct::class);
    }

    /**
     * Returns the Products available for the Color.
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, SpecificProduct::class);
    }
}
