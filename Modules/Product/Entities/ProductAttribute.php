<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Authenticatable
{
    protected $table = 'product_attribute';
    use SoftDeletes;

    protected $fillable = ['product_id', 'attribute_id', 'attr_value'];

    
    public function attributeData()
    {
    	return $this->hasOne("Modules\Category\Entities\BusinessCategoryField", "id", "attribute_id")->withTrashed();
    }

}