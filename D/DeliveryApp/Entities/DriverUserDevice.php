<?php

namespace Modules\DeliveryApp\Entities;

use Illuminate\Database\Eloquent\Model;

class DriverUserDevice extends Model
{
    protected $table = 'driver_user_devices';
    protected $primaryKey = 'id';

    protected $fillable = ['deliveryboy_id', 'device_type', 'device_id', 'login_time', 'logout_time'];
}
