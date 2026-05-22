<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'nome',
        'nome_en',
        'sottotitolo',
        'sottotitolo_en',
        'foto',
        'allineamento_media',
        'contenuto',
        'contenuto_en',
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
        'seo_title_en',
        'seo_description',
        'seo_description_en',
        'seo_image',
        'immagine',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getNomeAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('amministrazione*') && !empty($this->nome_en)) {
            return $this->nome_en;
        }
        return $value;
    }

    public function getSottotitoloAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('amministrazione*') && !empty($this->sottotitolo_en)) {
            return $this->sottotitolo_en;
        }
        return $value;
    }

    public function getContenutoAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('amministrazione*') && !empty($this->contenuto_en)) {
            return $this->contenuto_en;
        }
        return $value;
    }

    public function getSeoTitleAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('amministrazione*') && !empty($this->seo_title_en)) {
            return $this->seo_title_en;
        }
        return $value;
    }

    public function getSeoDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('amministrazione*') && !empty($this->seo_description_en)) {
            return $this->seo_description_en;
        }
        return $value;
    }
}
