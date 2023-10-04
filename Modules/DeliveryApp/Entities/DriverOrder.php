<?php

namespace Modules\DeliveryApp\Entities;

use Illuminate\Database\Eloquent\Model;

class DriverOrder extends Model
{
    protected $table = 'driver_orders';
    protected $primaryKey = 'id';

    protected $fillable = ['driver_id', 'type', 'order_id', 'assigned_date','start_time','end_time', 'status', 'is_completed'];

    public function order()
    {
        return $this->hasOne('Modules\Order\Entities\Order', 'id', 'order_id');
    }
}
