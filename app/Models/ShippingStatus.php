<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShippingStatus extends Model
{
    protected $fillable = [
        "name",
        "description"
    ];
}
