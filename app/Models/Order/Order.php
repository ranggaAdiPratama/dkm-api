<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryAddress;
use App\Models\Order\OrderDetail;
use App\Models\Payment;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';

    public function orderStatus()
    {
        return $this->belongsTo('App\Models\Order\OrderStatus','order_statuses_id');
    }

    public function deliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class);
    }

    public function OrderDetail()
    {
        return $this->hasOne('App\Models\Order\OrderDetail','id');
    }

    public function Payment()
    {
        return $this->belongsTo(Payment::class,'payment_id');
    }
}
