<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';


    public function Order()
    {
        return $this->hasOne('app\Models\Order\Order');
    }
}
