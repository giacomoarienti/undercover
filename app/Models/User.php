<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $password
 * @property string $birthday
 * @property string $street_name
 * @property string $street_number
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $country
 * @property bool $is_seller
 * @property string|null $company_name
 * @property string|null $vat
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpecificProduct> $cart
 * @property-read int|null $cart_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Phone> $phones
 * @property-read int|null $phones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReceptionMethod> $receptionMethods
 * @property-read int|null $reception_method_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsSeller($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStreetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStreetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereZipCode($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'birthday',
        'street_name',
        'street_number',
        'city',
        'state',
        'zip_code',
        'country',
        'is_seller',
        'company_name',
        'vat'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_seller' => 'boolean'
        ];
    }

    /**
     * Accessor to get the full address of the user.
     *
     * @return Attribute<string, never>
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::get(fn() => "{$this->street_name}, {$this->street_number}, {$this->city}, {$this->state}, {$this->zip_code}, {$this->country}");
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function receptionMethods(): HasMany
    {
        return $this->hasMany(ReceptionMethod::class);
    }

    /**
     * Returns reviews written by the User
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Returns the Coupons created by the User
     * @return HasMany
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Returns the Phones owned by the User
     * @return BelongsToMany
     */
    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(Phone::class, 'phone_user');
    }

    /**
     * Returns the Products sold by the seller User.
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(
            SpecificProduct::class,
            'specific_products_in_carts'
        )->withPivot('quantity');
    }

    /**
     * Add a SpecificProduct to the client's cart.
     *
     * @param SpecificProduct $product
     * @param int $quantity
     * @return void
     */
    public function addToCart(SpecificProduct $product, int $quantity = 1): void
    {
        // Check if the product is already in the cart
        $cartItem = $this->cart()->where('specific_product_id', $product->id)->first();

        if ($cartItem) {
            // If the product is already in the cart, increment the quantity
            $this->cart()->updateExistingPivot($product->id, [
                'quantity' => $cartItem->pivot->quantity + $quantity,
            ]);
        } else {
            // If the product is not in the cart, attach it with the specified quantity
            $this->cart()->attach($product->id, ['quantity' => $quantity]);
        }
    }

    /**
     * Remove a SpecificProduct from the client's cart.
     *
     * @param SpecificProduct $product
     * @return void
     */
    public function removeFromCart(SpecificProduct $product): void
    {
        $this->cart()->detach($product->id);
    }

    /**
     * Empty the client's cart.
     *
     * @return void
     */
    public function emptyCart(): void
    {
        $this->cart()->detach();
    }

    /**
     * Return true if the client's purchased the given Product.
     *
     * @param Product $product
     * @return bool
     */
    public function hasBoughtProduct(Product $product): bool
    {
        return $this->orders()->where('product_id', $product->id)->exists();
    }
}
