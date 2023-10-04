<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Authenticatable
{
    protected $table = 'category';
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'order', 'is_active'];

    // public function categoryData()
    // {
    // 	return $this->belongsTo("Modules\Category\Entities\BusinessCategory", "category_id");
    // }

    // public function parentCategoryData()
    // {
    //     return $this->belongsTo("Modules\Category\Entities\BusinessCategory", "main_category_id");
    // }

    // public function shopData()
    // {
    // 	return $this->belongsTo("Modules\Shop\Entities\BusinessShop", "shop_id");
    // }

}