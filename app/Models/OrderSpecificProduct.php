<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct query()
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property int $specific_product_id
 * @property string|null $deleted_at
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereSpecificProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderSpecificProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];
}
