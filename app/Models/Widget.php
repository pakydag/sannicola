<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'article_id',
        'titolo',
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
}
