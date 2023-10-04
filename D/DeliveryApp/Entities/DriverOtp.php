<?php

namespace Modules\DeliveryApp\Entities;

use Illuminate\Database\Eloquent\Model;

class DriverOtp extends Model
{
    protected $table = 'driver_otp';
    protected $primaryKey = 'id';

    protected $fillable = ['driver_id', 'otp', 'status'];
}
