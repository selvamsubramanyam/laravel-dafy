<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCategoryField extends Authenticatable
{
    protected $table = 'business_category_fields';
    use SoftDeletes;

    protected $fillable = ['category_id', 'field_name', 'field_value', 'is_filter', 'control', 'is_active','is_detail_filter'];

    public function products()
    {
        return $this->belongsToMany('Modules\Product\Entities\Product','product_attribute','attribute_id','product_id')->withPivot('attr_value')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo('Modules\Category\Entities\BusinessCategory','category_id');
    }
}