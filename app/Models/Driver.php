<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\models\User;

class Driver extends Model
{
    protected $table = 'drivers';


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
