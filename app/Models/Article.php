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
        'sottotitolo',
        'descrizione',
        'foto',
        'link',
        'allegato',
        'section_id',
        'slug',
        'visibile',
        'seo_title',
        'seo_description',
        'seo_image',
        'has_contact_form',
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
}
