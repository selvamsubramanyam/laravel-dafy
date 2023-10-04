<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class Status extends Authenticatable
{
    protected $table = 'statuses';
    protected $fillable = ['name', 'slug', 'description', 'postion', 'is_active'];

    // public function products()
    // {
    //     return $this->belongsToMany('Modules\Product\Entities\Product','product_attribute','attribute_id','product_id')->withPivot('attr_value')->withTimestamps();
    // }

    // public function category()
    // {
    //     return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    // }
}