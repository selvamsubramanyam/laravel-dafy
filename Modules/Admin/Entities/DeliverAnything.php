<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliverAnything extends Model
{
    use SoftDeletes;
    protected $table = 'deliver_anything';

    protected $fillable = ['user_id', 'order_no','deliver_addressid', 'pickup_addressid', 'buy_items', 'note', 'name', 'mobile', 'shop_name', 'shop_location', 'shop_latitude', 'shop_longitude', 'image', 'status','order_status','driver_id', 'notify'];

    public function user()
    {
        return $this->hasOne("Modules\Users\Entities\User",'id','user_id');
    }

    public function orderStatus()
    {
        return $this->hasOne('Modules\Order\Entities\Status', 'id', 'order_status');
    }

    public function deliveryData()
    {
        return $this->hasOne("Modules\Order\Entities\OrderDeliverAnything",'order_id','id');
    }

    public function deliverAddress()
    {
        return $this->hasOne("Modules\Admin\Entities\DeliverAddress", 'id', 'deliver_addressid');
    }

    public function pickupAddress()
    {
        return $this->hasOne("Modules\Admin\Entities\DeliverAddress", 'id', 'pickup_addressid');
    }
}
