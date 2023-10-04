<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Authenticatable
{
    protected $table = 'wishlist';
    protected $primaryKey = 'id';
    use SoftDeletes;
    
    protected $fillable = ['user_id', 'shop_id', 'product_id'];

    public function shopData()
    {
      return $this->hasOne('Modules\Shop\Entities\BusinessShop', 'id', 'shop_id');
    }

    public function productData()
    {
      return $this->hasOne('Modules\Product\Entities\Product', 'id', 'product_id');
    }

}