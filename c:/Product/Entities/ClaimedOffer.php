<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimedOffer extends Model
{
    protected $table = 'claimed_offers';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['offer_id', 'user_id', 'shop_id', 'shop_name', 'status'];

    public function shop()
    {
    	return $this->belongsTo("Modules\Shop\Entities\BusinessShop","shop_id");
    }

    public function offer()
    {
    	return $this->belongsTo("Modules\Admin\Entities\Offer","offer_id");
    }

    public function user()
    {
    	return $this->belongsTo("Modules\Users\Entities\User","user_id");
    }
    
}