<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'titolo',
        'titolo_en',
        'sottotitolo',
        'sottotitolo_en',
        'descrizione',
        'descrizione_en',
        'foto',
        'video',
        'allineamento_media',
        'link',
        'allegato',
        'section_id',
        'slug',
        'visibile',
        'mostra_data',
        'seo_title',
        'seo_title_en',
        'seo_description',
        'seo_description_en',
        'seo_image',
        'has_contact_form',
        'has_transfer_form',
        'has_car_rental_form',
        'ordine',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function widgets()
    {
        return $this->hasMany(Widget::class)->orderBy('ordine');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(300)
              ->height(300)
              ->sharpen(10);

        $this->addMediaConversion('webp')
              ->format('webp')
              ->quality(80);
    }

    public function getTitoloAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->titolo_en)) {
            return $this->titolo_en;
        }
        return $value;
    }

    public function getSottotitoloAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->sottotitolo_en)) {
            return $this->sottotitolo_en;
        }
        return $value;
    }

    public function getDescrizioneAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->descrizione_en)) {
            return $this->descrizione_en;
        }
        return $value;
    }

    public function getSeoTitleAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->seo_title_en)) {
            return $this->seo_title_en;
        }
        return $value;
    }

    public function getSeoDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->seo_description_en)) {
            return $this->seo_description_en;
        }
        return $value;
    }
}
