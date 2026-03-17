<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bOrder extends Model
{
    protected $fillable = ['agent_id', 'b2b_customer_id', 'total_amount', 'status', 'payment_method', 'notes'];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function customer()
    {
        return $this->belongsTo(B2bCustomer::class, 'b2b_customer_id');
    }

    public function items()
    {
        return $this->hasMany(B2bOrderItem::class, 'b2b_order_id');
    }
}
