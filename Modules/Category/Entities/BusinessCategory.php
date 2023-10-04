<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class BusinessCategory extends Authenticatable
{
    protected $table = 'business_categories';
    use SoftDeletes;

    protected $fillable = ['seller_id', 'name', 'parent_name', 'slug', 'description', 'image', 'icon', 'parent_id', 'is_last_child', 'module_id', 'order', 'is_active'];

    public function products()
    {
        return $this->belongsToMany('Modules\Product\Entities\Product','product_categories','category_id','product_id')->withPivot('shop_id')->withTimestamps(); ;
    }

    public function childrens()
    {
      return $this->hasMany('Modules\Category\Entities\BusinessCategory', 'parent_id')->orderBy('order', 'ASC');
    }

    public function categoryAttributes()
    {
      return $this->hasMany('Modules\Category\Entities\BusinessCategoryField', 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany('Modules\Users\Entities\User','business_seller_categories','main_category_id','user_id')->withTimestamps(); 
    }
}