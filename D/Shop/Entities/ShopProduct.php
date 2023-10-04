<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class ShopProduct extends Authenticatable
{
    protected $table = 'shop_products';

    protected $fillable = ['shop_id', 'category_id', 'product_id', 'name', 'variation_id', 'selling_price', 'stock', 'is_active'];

    public function variationData()
    {
    	return $this->belongsTo("Modules\Product\Entities\Variation","variation_id");
    }
}