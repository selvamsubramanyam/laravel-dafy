<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['seller_id','title', 'discount_type', 'discount_value', 'max_discount_value', 'min_amount_price', 'valid_from', 'valid_to', 'description', 'about', 'image', 'categories', 'products', 'status'];

    public function offerProducts()
    {
    	return $this->hasMany("Modules\Product\Entities\OfferProduct","offer_id");
    }

    public function shop()
    {
    	return $this->belongsTo("Modules\Shop\Entities\BusinessShop","seller_id");
    }
    
}