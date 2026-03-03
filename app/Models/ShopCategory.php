<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = ['nome', 'slug', 'parent_id', 'visibile', 'ordine'];

    public function parent()
    {
        return $this->belongsTo(ShopCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ShopCategory::class, 'parent_id');
    }
}
