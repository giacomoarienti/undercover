<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Illuminate\Events\queueable;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct withoutTrashed()
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecificProduct whereSlug($value)
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

    protected $with = ['product', 'color'];

    protected static function booted() : void {
        static::creating(function (SpecificProduct $specificProduct) {
           $specificProduct->slug = $specificProduct->product->slug . '-' . $specificProduct->color->slug;
        });

        static::restoring(function (SpecificProduct $specificProduct) {
            error_log("SpecificProduct " . $specificProduct->slug . " restored");
        });

        static::deleting(function (SpecificProduct $specificProduct) {
           error_log("SpecificProduct " . $specificProduct->slug . " deleted");
        });

        static::deleted(queueable(function (SpecificProduct $specificProduct) {
            error_log("SpecificProduct " . $specificProduct->slug . " deleted");
            $specificProduct->product->user->sendNotification("Your product " . $specificProduct->product->name . " has depleted.",
                "You just sold the last item with color " . $specificProduct->color->name . ".");
            $usersWithSpecificProduct = User::whereHas('cart', function ($query) use ($specificProduct) {
                $query->where('specific_product_id', $specificProduct->id);
            })->each(function ($user) use ($specificProduct) {
                $user->sendNotification("A product in your cart has depleted.",
                    "The product " . $specificProduct->product->name . " with color " . $specificProduct->color->name . " has depleted and was removed from your cart.");
                $user->removeFromCart($specificProduct);
            });
        }));
    }

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

    public function buy(int $amount = 1)
    {
        $this->quantity--;
        if($this->quantity == 0) {
            $this->delete();
        } else {
            $this->save();
        }
    }
}
