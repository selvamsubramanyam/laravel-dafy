<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class OrderAddress extends Authenticatable
{
    protected $table = 'order_address';
    protected $fillable = ['order_id', 'user_id', 'name', 'build_name', 'area', 'location', 'landmark', 'latitude', 'longitude', 'mobile', 'pincode', 'type'];

    // public function products()
    // {
    //     return $this->belongsToMany('Modules\Product\Entities\Product','product_attribute','attribute_id','product_id')->withPivot('attr_value')->withTimestamps();
    // }

    // public function category()
    // {
    //     return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    // }

    public function order()
    {
        return $this->belongsTo('Modules\Order\Entities\Order','order_id');
    }
}