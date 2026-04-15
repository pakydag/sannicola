<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    protected $fillable = [
        'nome', 'slug', 'shop_category_id', 'shop_collection_id', 'shop_brand_id',
        'marca', 'descrizione', 'foto_aggiuntive', 'sku_padre', 'visibile', 'ordine'
    ];

    protected $casts = [
        'foto_aggiuntive' => 'array',
        'visibile' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    public function collection()
    {
        return $this->belongsTo(ShopCollection::class, 'shop_collection_id');
    }

    public function brand()
    {
        return $this->belongsTo(ShopBrand::class, 'shop_brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ShopVariant::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'shop_product_tags');
    }

    public function getLowestPrice()
    {
        $minPrice = $this->variants->min('prezzo');
        $minDiscounted = $this->variants->where('prezzo_scontato', '>', 0)->min('prezzo_scontato');

        if ($minDiscounted && $minDiscounted < $minPrice) {
            return [
                'prezzo' => $minPrice,
                'prezzo_scontato' => $minDiscounted
            ];
        }

        return [
            'prezzo' => $minPrice,
            'prezzo_scontato' => null
        ];
    }
}
