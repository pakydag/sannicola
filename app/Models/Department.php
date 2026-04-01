<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'email',
        'working_hours',
        'appointment_duration',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'working_hours' => 'array',
        'appointment_duration' => 'integer',
    ];

    /**
     * Get the appointments for the department.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Sincronizza con Vapi solo se i campi rilevanti cambiano
        static::saved(function ($department) {
            app(\App\Services\VapiService::class)->syncAssistantConfig();
        });

        static::deleted(function ($department) {
            app(\App\Services\VapiService::class)->syncAssistantConfig();
        });
    }
}
