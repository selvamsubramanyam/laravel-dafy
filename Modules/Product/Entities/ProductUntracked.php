<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUntracked extends Model
{
    use SoftDeletes;
    protected $table = 'product_untracked';
    protected $fillable = ['sku', 'name', 'seller_id','brand','description', 'vendor_price','price', 'stock','thump_image','unit_measurement','measurement_value','product_type','configurable_variations','additional_attributes','commission'];
    protected $dates = ['deleted_at'];

    public function untrackImages()
    {
        return $this->hasMany('Modules\Product\Entities\ProductUntrackedImages','product_id');
    }

    public function trackedProducts()
    {
        return $this->hasMany('Modules\Product\Entities\Product','seller_id','seller_id');
    }

    

}
