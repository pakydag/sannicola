<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'article_id',
        'titolo',
        'titolo_en',
        'tipo',
        'ordine',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function getTitoloAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->attributes['titolo_en'])) {
            return $this->attributes['titolo_en'];
        }
        return $value;
    }

    public function getDataAttribute($value)
    {
        $data = json_decode($value, true) ?: [];
        if (app()->getLocale() === 'en') {
            foreach ($data as $key => $val) {
                if (!empty($data[$key . '_en'])) {
                    $data[$key] = $data[$key . '_en'];
                }
            }
        }
        return $data;
    }
}
