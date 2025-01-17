<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderSpecificProduct query()
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
