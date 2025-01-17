<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int $quantity
 * @property int $product_id
 * @property int $color_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Color $color
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SpecificProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "quantity",
        "product_id",
        "color_id"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
