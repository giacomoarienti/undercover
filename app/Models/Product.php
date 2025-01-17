<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property int $material_id
 * @property int $phone_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Material $material
 * @property-read \App\Models\Phone $phone
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpecificProduct> $specificProducts
 * @property-read int|null $specific_products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePhoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 * @mixin \Eloquent
 */
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

    public function updateColors(array $colors) : void
    {
        foreach ($this->specificProducts as $specificProduct) {
            if(!array_key_exists($specificProduct->color->id, $colors)) {
                $specificProduct->delete();
            }
        }
        foreach ($colors as $color) {
            $specificProduct = SpecificProduct::firstOrNew(['product_id' => $this->id, 'color_id' => $color["color"]]);
            $specificProduct->quantity = $color["quantity"];
            $specificProduct->save();
        }
    }
}
