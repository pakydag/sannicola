<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nome', 'slug'];

    public function products()
    {
        return $this->belongsToMany(ShopProduct::class, 'shop_product_tags');
    }
}
