<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalWidget extends Model
{
    protected $fillable = [
        'titolo',
        'titolo_en',
        'tipo',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

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
