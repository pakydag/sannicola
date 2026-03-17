<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bProduct extends Model
{
    protected $fillable = ['name', 'b2b_brand_id', 'season', 'description', 'image', 'has_stock', 'is_active', 'price'];

    public function brand()
    {
        return $this->belongsTo(B2bBrand::class, 'b2b_brand_id');
    }

    public function variants()
    {
        return $this->hasMany(B2bProductVariant::class, 'b2b_product_id');
    }
}
