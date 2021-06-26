<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;

class DeliveryAddress extends Model
{
    protected $table = 'delivery_addresses';


    public function User()
    {
       return $this->hasOne(User::class);
    }
}
