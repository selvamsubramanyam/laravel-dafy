<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class Order extends Authenticatable
{
    protected $table = 'order';
    protected $fillable = ['order_no', 'invoice_no', 'invoice_date', 'razorpay_order_id', 'user_id', 'shop_id', 'instore', 'coupon_id', 'coupon_price', 'payment_method', 'discount', 'discount_percent', 'tax_amount', 'points', 'status_id', 'delivery_date', 'invoice', 'delivery_fee', 'amount', 'comments', 'reason', 'is_reverted', 'is_active','grand_total', 'admin_id', 'notify'];

    public function orderProducts()
    {
        return $this->hasMany('Modules\Order\Entities\OrderProduct', 'order_id', 'id');
    }

    public function orderStatus()
    {
        return $this->hasOne('Modules\Order\Entities\Status', 'id', 'status_id');
    }

    public function shop()
    {
        return $this->hasOne('Modules\Shop\Entities\BusinessShop', 'id', 'shop_id');
    }

    public function user()
    {
        return $this->hasOne('Modules\Users\Entities\User', 'id', 'user_id');
    }

    public function orderAddresses()
    {
        return $this->hasOne('Modules\Order\Entities\OrderAddress','order_id');
    }

    public function orderAddressesView()
    {
        return $this->hasMany('Modules\Order\Entities\OrderAddress','order_id');
    }

    public function eachStatus()
    {
        return $this->hasMany('Modules\Order\Entities\OrderStatus', 'order_id', 'id');
    }

    // public function category()
    // {
    //     return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    // }

    public function drivers()
    {
        return $this->belongsToMany('Modules\DeliveryApp\Entities\DriverUser','driver_orders','order_id','driver_id')->withPivot('assigned_date','start_time','end_time','status')->withTimestamps(); ;
    }
}