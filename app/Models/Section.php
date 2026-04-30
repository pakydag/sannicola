<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'nome',
        'sottotitolo',
        'contenuto',
        'ordine',
        'visibile',
        'tipo',
        'modulo',
        'menu_a_tendina',
        'colonne_griglia',
        'mostra_nel_menu',
        'mostra_nel_footer',
        'slug',
        'seo_title',
        'seo_description',
        'seo_image',
        'immagine',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
