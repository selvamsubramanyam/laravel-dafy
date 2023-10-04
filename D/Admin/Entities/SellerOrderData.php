<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SellerOrderData extends Model
{
    protected $table = 'seller_order_data';
    protected $primaryKey = 'id';

    protected $fillable = ['seller_id', 'type', 'order_id', 'total', 'amount', 'commission', 'status', 'is_paid'];

    public function orderData()
    {
        return $this->hasOne("Modules\Order\Entities\Order", 'id', 'order_id');
    }

    public function sellerData()
    {
        return $this->hasOne("Modules\Shop\Entities\BusinessShop", 'id', 'seller_id');
    }

}