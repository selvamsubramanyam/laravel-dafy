<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderDeliverAnything extends Model
{
    protected $table = 'order_deliver_anything';
    protected $fillable = ['order_id', 'driver_id', 'status_id', 'assign_date'];

    public function orderData()
    {
        return $this->hasOne('Modules\Admin\Entities\DeliverAnything', 'id', 'order_id');
    }

    public function orderStatus()
    {
        return $this->hasOne('Modules\Order\Entities\Status', 'id', 'status_id');
    }
}
