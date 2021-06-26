<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Order\Order;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    // public function orderStatus()
    // {
    //     return $this->belongsTo('App\Models\Order\order','order_statuses_id');
    // }
}
