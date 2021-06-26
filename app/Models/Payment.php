<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Order;

class Payment extends Model
{
    protected $table = 'payments';

    // public function orderStatus()
    // {
    //     return $this->belongsTo('App\Models\Order\OrderStatus','order_statuses_id');
    // }

    // public function order()
    // {
    //     return $this->hasOne(Order::class,'id');
    // }
}
