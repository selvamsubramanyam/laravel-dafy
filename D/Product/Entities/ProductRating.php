<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRating extends Model
{
    protected $table = 'product_ratings';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['user_id', 'product_id', 'rating', 'review'];

    // public function offerData()
    // {
    // 	return $this->hasOne("Modules\Admin\Entities\Offer", "id", "offer_id");
    // }

    // public function shopData()
    // {
    // 	return $this->hasOne("Modules\Shop\Entities\BusinessShop", "id", "shop_id");
    // }

    public function product()
    {
    	return $this->hasOne("Modules\Product\Entities\Product", 'id',"product_id");
    }

    public function user()
    {
    	return $this->hasOne("Modules\Users\Entities\User", 'id',"user_id");
    }


    
}