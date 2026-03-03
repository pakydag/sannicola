<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'nome',
        'contenuto',
        'ordine',
        'visibile',
        'tipo',
        'menu_a_tendina',
        'colonne_griglia',
        'slug',
        'seo_title',
        'seo_description',
        'seo_image',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
