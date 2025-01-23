<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Interaction;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property string $slug
 * @property-read mixed $media_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @property-read mixed $url
 * @mixin \Eloquent
 */
class Product extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }

    protected $fillable = [
        "name",
        "description",
        "price",
        "material_id",
        "phone_id",
        "user_id",
    ];

    protected $with = ['material', 'phone'];

    protected $appends = ['media_url', "url"];

    // Specify use of slug column for routes
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($product) {
           $product->slug = Str::slug($product->user->id . " " . $product->name);
        });

        static::deleting(function ($product) {
            $product->specificProducts()->each(
                fn ($specificProduct) => $specificProduct->delete()
            );
        });
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('products.show', $this->slug)
        );
    }

    protected function mediaUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('images', 'thumb')
        );
    }

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
     * Return the Reviews of the Product.
     * @return HasMany
     */
    public function reviews() : HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function updateColors(array $colors) : void
    {
        foreach ($this->specificProducts as $specificProduct) {
            if(!collect($colors)->contains("color_id", $specificProduct->color_id)) {
                $specificProduct->delete();
            }
        }
        foreach ($colors as $color) {
            //restore the model if it was previously available
            SpecificProduct::onlyTrashed()->where(['product_id' => $this->id, 'color_id' => $color["color_id"]])->restore();

            $specificProduct = SpecificProduct::firstOrNew(['product_id' => $this->id, 'color_id' => $color["color_id"]]);
            $specificProduct->quantity = $color["quantity"];
            $specificProduct->save();
        }
    }

    public function hasColor(Color $color) : bool
    {
        return $this->specificProducts->contains("color_id", $color->id);
    }

    public function reviewsCount() : int
    {
        return $this->reviews->count();
    }

    public function reviewsAverage() : float
    {
        return $this->reviews->avg('stars') ?? 0;
    }
}
