<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCategoryShop extends Authenticatable
{
    protected $table = 'category_shops';
    use SoftDeletes;

    protected $fillable = ['main_category_id', 'category_id', 'shop_id', 'view_type'];

    public function categoryData()
    {
    	return $this->belongsTo("Modules\Category\Entities\BusinessCategory", "category_id")->withTrashed();
    }

    public function parentCategoryData()
    {
        return $this->belongsTo("Modules\Category\Entities\BusinessCategory", "main_category_id")->withTrashed();
    }

    public function shopData()
    {
    	return $this->belongsTo("Modules\Shop\Entities\BusinessShop", "shop_id");
    }

}