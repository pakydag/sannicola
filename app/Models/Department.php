<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'email',
        'is_active',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saved(function ($department) {
            app(\App\Services\VapiService::class)->syncAssistantConfig();
        });

        static::deleted(function ($department) {
            app(\App\Services\VapiService::class)->syncAssistantConfig();
        });
    }
}
