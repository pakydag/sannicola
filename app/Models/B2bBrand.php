<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bBrand extends Model
{
    protected $fillable = ['name'];

    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_brand', 'b2b_brand_id', 'user_id');
    }
}
