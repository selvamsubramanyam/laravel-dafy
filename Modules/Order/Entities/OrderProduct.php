<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class OrderProduct extends Authenticatable
{
    protected $table = 'order_products';
    protected $fillable = ['order_id', 'product_id', 'product_price', 'product_discount', 'product_quantity', 'tot_price', 'tot_discount'];

    public function productData()
    {
        return $this->hasOne('Modules\Product\Entities\Product', 'id', 'product_id')->withTrashed();
    }
    
    public function product()
    {
    	return $this->hasOne("Modules\Product\Entities\Product", 'id',"product_id")->withTrashed();
    }

    // public function category()
    // {
    //     return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    // }
}