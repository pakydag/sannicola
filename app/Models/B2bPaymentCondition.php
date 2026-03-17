<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bPaymentCondition extends Model
{
    protected $fillable = ['name', 'description'];

    public function customers()
    {
        return $this->hasMany(B2bCustomer::class, 'payment_condition_id');
    }
}
