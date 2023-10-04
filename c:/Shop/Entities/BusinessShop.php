<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class BusinessShop extends Authenticatable
{

    use Notifiable;
    protected $guard = 'seller';
    protected $table = 'business_shops';
    protected $primaryKey = 'id';

    protected $fillable = ['seller_id', 'code', 'type', 'instore', 'instore_description', 'name', 'phone_no', 'services', 'location', 'address', 'latitude', 'longitude', 'delivery_distance', 'area_id', 'city_id', 'pincode', 'description', 'email', 'website', 'latitude', 'longitude', 'parent_id', 'share_url', 'is_active', 'order' ,'password','image'];

    public function images()
    {
    	return $this->hasMany("Modules\Shop\Entities\ShopImage", "shop_id", "id");
    }

    public function getArea()
    {
    	return $this->hasOne("Modules\Admin\Entities\Area", "id", "area_id");
    }

    public function getCity()
    {
    	return $this->hasOne("Modules\Admin\Entities\City", "id", "city_id");
    }

    public function products()
    {
    	return $this->hasMany("Modules\Product\Entities\Product", "seller_id", "id");
    }

    public function sellerInfo()
    {
        return $this->hasOne("Modules\Users\Entities\User", 'id', 'seller_id');
    }

    public function offerShops()
    {
        return $this->hasMany("Modules\Product\Entities\OfferProduct", "shop_id", "id");
    }

    public function offers()
    {
        return $this->hasMany("Modules\Admin\Entities\Offer","seller_id");
    }

    public function shopCategories()
    {
        return $this->hasMany("Modules\Shop\Entities\BusinessCategoryShop","shop_id");
    }

    
    public function orders()
    {
    	return $this->hasMany("Modules\Order\Entities\Order", "shop_id", "id");
    }

    public function sellerReport()
    {
        return $this->hasMany("Modules\Admin\Entities\SellerOrderData", "seller_id", "id");
    }

}