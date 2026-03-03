<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopVariant extends Model
{
    protected $fillable = [
        'shop_product_id', 'sku', 'ean', 'colore', 'taglia', 
        'prezzo', 'prezzo_scontato', 'quantita', 'foto'
    ];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'shop_product_id');
    }
}
