<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bCustomer extends Model
{
    protected $fillable = [
        'business_name', 'vat_number', 'contact_name', 
        'contact_surname', 'phone', 'email', 'payment_condition_id'
    ];

    public function paymentCondition()
    {
        return $this->belongsTo(B2bPaymentCondition::class, 'payment_condition_id');
    }
}
