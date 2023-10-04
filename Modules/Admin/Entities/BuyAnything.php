<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyAnything extends Model
{
    use SoftDeletes;
    protected $table = 'buy_anything';
    protected $fillable = ['order_no','user_id', 'deliver_addressid', 'buy_items', 'name', 'mobile', 'shop_name', 'shop_location', 'shop_latitude', 'shop_longitude', 'image', 'status','order_status','driver_id', 'notify'];
    protected $dates = ['deleted_at'];

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
        return $this->hasOne("Modules\Order\Entities\OrderBuyAnything",'order_id','id');
    }

    public function buyAddress()
    {
        return $this->hasOne("Modules\Admin\Entities\BuyAddress", 'id', 'deliver_addressid');
    }

  
}
