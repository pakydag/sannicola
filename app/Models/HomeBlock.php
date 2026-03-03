<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeBlock extends Model
{
    protected $fillable = ['global_widget_id', 'ordine'];

    public function globalWidget()
    {
        return $this->belongsTo(GlobalWidget::class);
    }
}
