<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShopOrder;
use App\Models\ShopVariant;

class ShopOrderItem extends Model
{
    protected $fillable = [
        'shop_order_id', 'shop_variant_id', 'nome_prodotto', 'sku', 'colore', 'taglia',
        'quantita', 'prezzo_unitario', 'subtotale'
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'shop_order_id');
    }

    public function variant()
    {
        return $this->belongsTo(ShopVariant::class, 'shop_variant_id');
    }
}
