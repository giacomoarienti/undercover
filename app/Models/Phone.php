<?php

namespace App\Models;

use Database\Factories\PhoneFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Post
 *
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $brand_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\PhoneFactory factory($count = null, $state = [])
 * @method static Builder<static>|Phone newModelQuery()
 * @method static Builder<static>|Phone newQuery()
 * @method static Builder<static>|Phone query()
 * @method static Builder<static>|Phone whereBrandId($value)
 * @method static Builder<static>|Phone whereCreatedAt($value)
 * @method static Builder<static>|Phone whereId($value)
 * @method static Builder<static>|Phone whereName($value)
 * @method static Builder<static>|Phone whereSlug($value)
 * @method static Builder<static>|Phone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
