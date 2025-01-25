<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Brand
 *
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Phone> $phones
 * @property-read int|null $phones_count
 * @method static Builder<static>|Brand newModelQuery()
 * @method static Builder<static>|Brand newQuery()
 * @method static Builder<static>|Brand query()
 * @method static Builder<static>|Brand whereCreatedAt($value)
 * @method static Builder<static>|Brand whereId($value)
 * @method static Builder<static>|Brand whereName($value)
 * @method static Builder<static>|Brand whereSlug($value)
 * @method static Builder<static>|Brand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Brand extends Model
{
    protected $fillable = [
        "name",
        "slug",
    ];

    //TODO: modificare factory per usare funzionalità di auto-slugging
    protected static function booted() : void {
        static::creating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });
    }

    public function phones() : HasMany {
        return $this->hasMany(Phone::class);
    }

    public function addPhone(String $name) : ?Phone {
        $phone = $this->phones()->where(['name' => $name])->first();
        if(!$phone) {
            $phone = Phone::create([
                "name" => $name,
                "brand_id" => $this->id
            ]);
        }
        return $phone;
    }
}
