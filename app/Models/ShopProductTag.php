<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ShopProductTag extends Pivot
{
    protected $table = 'shop_product_tags';
    protected $fillable = ['shop_product_id', 'tag_id'];
}
