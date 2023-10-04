<?php

namespace Modules\DeliveryApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class DriverUser extends Authenticatable
{
    protected $table = 'driver_users';
    protected $fillable = ['country_code','mobile','alt_country_code','alt_mobile', 'name', 'email', 'location', 'area', 'build_name', 'landmark', 'latitude', 'longitude', 'city', 'address', 'password', 'is_busy', 'is_active', 'image'];

    public function orders()
    {
        return $this->belongsToMany('Modules\Order\Entities\Order','driver_orders','driver_id','order_id')->withPivot('assigned_date','start_time','end_time','status')->withTimestamps(); 
    }
}
