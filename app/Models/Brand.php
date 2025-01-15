<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Client\Response;

class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
    ];

    public function phones() : HasMany {
        return $this->hasMany(Phone::class);
    }

    public function addPhone(String $name) : ?Phone {
        if($this->phones()->has('name', $name)->doesntExist()) {
            return Phone::create([
                "name" => $name,
                "slug" => $name,
                "brand_id" => $this->id
            ]);
        }
        return null;
    }
}
