<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class FavouriteShop extends Authenticatable
{
    protected $table = 'favourite_shops';
    protected $fillable = ['user_id', 'shop_id'];

    public function shopData()
    {
      return $this->hasOne('Modules\Shop\Entities\BusinessShop', 'id', 'shop_id');
    }
}