<?php

namespace Modules\DeliveryApp\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderOtp extends Model
{
    protected $table = 'order_otp';
    protected $primaryKey = 'id';

    protected $fillable = ['driver_id', 'user_id','otp', 'status'];
}
