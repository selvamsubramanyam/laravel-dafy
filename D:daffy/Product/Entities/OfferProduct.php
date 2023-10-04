<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferProduct extends Model
{
    protected $table = 'offer_products';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['offer_id', 'shop_id', 'product_id', 'type'];

    public function offerData()
    {
    	return $this->hasOne("Modules\Admin\Entities\Offer", "id", "offer_id");
    }

    public function shopData()
    {
    	return $this->hasOne("Modules\Shop\Entities\BusinessShop", "id", "shop_id");
    }
    
}