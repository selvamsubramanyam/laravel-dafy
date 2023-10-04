<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Authenticatable
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['type', 'sku', 'name', 'seller_id','brand_id','slug',  'description', 'vendor_price','price', 'offer', 'stock',  'is_active','thump_image','unit_id','measurement_unit','configurable_variations', 'parent_id','cat_varient_id','is_approved','commission'];

    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    public function categoryData()
    {
    	return $this->belongsTo("Modules\Category\Entities\BusinessCategory", "category_id")->withTrashed();
    }

    public function categories()
    {
        return $this->belongsToMany('Modules\Category\Entities\BusinessCategory','product_categories','product_id','category_id')->withPivot('shop_id')->withTimestamps(); 
    }
    public function productImages()
    {
        return $this->hasMany('Modules\Product\Entities\ProductImage','product_id');
    }

    public function brand()
    {
    	return $this->belongsTo("Modules\Admin\Entities\Brand");
    }
    public function shop()
    {
    	return $this->belongsTo("Modules\Shop\Entities\BusinessShop","seller_id");
    }

    public function untrackedProducts()
    {
    	return $this->belongsTo("Modules\Product\Entities\ProductUntracked","seller_id","seller_id");
    }

    public function unit()
    {
    	return $this->belongsTo("Modules\Admin\Entities\Unit");
    }

    public function attributes()
    {
        return $this->belongsToMany('Modules\Category\Entities\BusinessCategoryField','product_attribute','product_id','attribute_id')->withPivot('attr_value')->withTimestamps();
    }

    public function parentProduct()
    {
        return $this->belongsTo('Modules\Product\Entities\Product','parent_id','id')->where('parent_id',0)->with('parentProduct');;
    }

    public function children() {
        return $this->hasMany('Modules\Product\Entities\Product','parent_id')->with('children');
      }

    public function offerShops() {
        return $this->hasMany('Modules\Product\Entities\OfferProduct', 'shop_id', 'seller_id');
    }

    public function toggleIsActive()
    {
        $this->is_active= !$this->is_active;
        return $this;
    }

    public function productRatings()
    {
        return $this->hasMany('Modules\Product\Entities\ProductRating', 'product_id', 'id');
    }

    public function keywords()
    {
        return $this->hasMany('Modules\Users\Entities\TrendKeyword', 'product_id', 'id');
    }



}