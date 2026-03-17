<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'role',
        'is_super_admin',
        'can_manage_site',
        'can_manage_shop',
        'can_manage_booking',
        'can_manage_voip',
        'can_manage_agents',
    ];

    /**
     * Get the brands that the agent is authorized to view.
     */
    public function b2bBrands()
    {
        return $this->belongsToMany(B2bBrand::class, 'agent_brand', 'user_id', 'b2b_brand_id');
    }

    /**
     * Get the customers that the agent is authorized to manage.
     */
    public function b2bCustomers()
    {
        return $this->belongsToMany(B2bCustomer::class, 'agent_customer', 'user_id', 'b2b_customer_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
