<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Authenticatable
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    use SoftDeletes;
    
    protected $fillable = ['user_id', 'product_id', 'seller_id', 'quantity', 'instore'];

    public function getProduct()
    {
    	return $this->hasOne("Modules\Product\Entities\Product", "id", "product_id");
    }

    public function getSeller()
    {
    	return $this->hasOne("Modules\Shop\Entities\BusinessShop", "id", "seller_id");
    }

}