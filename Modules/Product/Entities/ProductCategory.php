<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Support\Str;

class ProductCategory extends Authenticatable
{
    protected $table = 'product_categories';
    protected $fillable = ['product_id', 'category_id', 'shop_id'];

    
    public function productData()
    {
    	return $this->hasOne("Modules\Product\Entities\Product", "id", "product_id");
    }

    public function categoryData()
    {
    	return $this->hasOne("Modules\Category\Entities\BusinessCategory", "id", "category_id");
    }

}