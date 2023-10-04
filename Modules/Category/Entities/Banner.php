<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Authenticatable
{
    protected $table = 'business_banners'; 
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['module_id', 'shop_id', 'product_id', 'title', 'image', 'description', 'valid_from', 'valid_to', 'is_active'];
    protected $attributes = array( 'module_id' => 1 );
}