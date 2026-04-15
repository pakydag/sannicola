<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopBrand extends Model
{
    protected $fillable = [
        'nome', 'slug', 'foto', 'descrizione', 'visibile', 'ordine'
    ];

    protected $casts = [
        'visibile' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(ShopProduct::class);
    }
}
