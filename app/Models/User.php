<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'is_vendor',
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
            'is_vendor' => 'boolean'
        ];
    }

    public function notifications() : HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function paymentMethods() : HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function receptionMethod(): HasMany
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
     * Add a SpecificProduct to the user's cart.
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
}
