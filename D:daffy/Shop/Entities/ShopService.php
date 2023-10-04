<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class ShopService extends Authenticatable
{
    protected $table = 'shop_services';

    protected $fillable = ['shop_id', 'service_names'];

}