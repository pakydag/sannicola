<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Contact extends Authenticatable
{
    use HasFactory, Notifiable;
    
    public $skipSpokiSync = false;

    protected static function booted()
    {
        static::saving(function ($contact) {
            $contact->syncSystemTags();
        });

        static::saved(function ($contact) {
            if (!$contact->skipSpokiSync) {
                // Dispatch async sync to Spoki
                \App\Jobs\SyncContactToSpoki::dispatch($contact);
            }
        });
    }

    public function syncSystemTags()
    {
        $tags = $this->tags ?? [];
        
        $map = [
            'is_shop_customer' => 'Shop',
            'is_booking_customer' => 'Booking',
            'is_b2b_customer' => 'B2B',
            'is_lead' => 'Lead',
            'is_vapi_lead' => 'Agente AI',
        ];

        // Normalize existing tags to avoid case issues
        $normalizedTags = array_map('strtolower', $tags);

        foreach ($map as $field => $tagName) {
            if ($this->{$field} && !in_array(strtolower($tagName), $normalizedTags)) {
                $tags[] = $tagName;
            }
        }

        $this->tags = array_values(array_unique($tags));
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'password',
        'tax_id',
        'vat_number',
        'sdi_code',
        'pec',
        'address',
        'zip_code',
        'city',
        'province',
        'country',
        'is_shop_customer',
        'is_booking_customer',
        'is_b2b_customer',
        'is_lead',
        'is_vapi_lead',
        'is_active',
        'tags',
        'meta_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_shop_customer' => 'boolean',
        'is_booking_customer' => 'boolean',
        'is_b2b_customer' => 'boolean',
        'is_lead' => 'boolean',
        'is_vapi_lead' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'meta_data' => 'array',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function shopOrders()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function b2bOrders()
    {
        return $this->hasMany(B2bOrder::class);
    }

    public function aiTickets()
    {
        return $this->hasMany(AiTicket::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
