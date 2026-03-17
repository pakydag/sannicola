<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bProductVariant extends Model
{
    protected $fillable = ['b2b_product_id', 'color', 'size', 'quantity'];

    public function product()
    {
        return $this->belongsTo(B2bProduct::class, 'b2b_product_id');
    }
}
