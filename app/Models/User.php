<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'vat',
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
}
