<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function orderStatus()
    {
        return $this->belongsTo('App\Models\Order\OrderStatus','order_statuses_id');
    }
}
