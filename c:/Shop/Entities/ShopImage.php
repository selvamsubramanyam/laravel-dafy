<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class ShopImage extends Authenticatable
{
    protected $table = 'shop_images';

    protected $fillable = ['shop_id', 'image'];

}