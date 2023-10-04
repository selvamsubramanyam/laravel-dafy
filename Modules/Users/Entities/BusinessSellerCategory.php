<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessSellerCategory extends Authenticatable
{
    protected $table = 'business_seller_categories';
    protected $primaryKey = 'id';
    use SoftDeletes;
    
    protected $fillable = ['user_id', 'main_category_id'];

    public function getCategory()
    {
    	return $this->hasOne("Modules\Category\Entities\BusinessCategory", "id", "main_category_id")->withTrashed();
    }
    
}