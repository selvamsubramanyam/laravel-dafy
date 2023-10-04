<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class OrderStatus extends Authenticatable
{
    protected $table = 'order_status';
    protected $fillable = ['order_id', 'order_product_id', 'status_id'];

    // public function products()
    // {
    //     return $this->belongsToMany('Modules\Product\Entities\Product','product_attribute','attribute_id','product_id')->withPivot('attr_value')->withTimestamps();
    // }

    // public function category()
    // {
    //     return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    // }
    public function status()
    {
        return $this->hasOne('Modules\Order\Entities\Status','id','status_id');
    }
    
}