<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCollection extends Model
{
    protected $fillable = ['nome', 'slug', 'foto', 'visibile', 'ordine'];

    public function products()
    {
        return $this->hasMany(ShopProduct::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'shop_collection_tags');
    }
}
