<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalWidget extends Model
{
    protected $fillable = [
        'titolo',
        'tipo',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
