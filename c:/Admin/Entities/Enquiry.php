<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiries';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'shop_id', 'category_id', 'subcategory_id', 'location', 'mobile', 'product_detail', 'product_name', 'expected_purchase', 'status'];

    public function shop()
    {
        return $this->hasOne('Modules\Shop\Entities\BusinessShop', 'id', 'shop_id');
    }

    public function user()
    {
        return $this->hasOne('Modules\Users\Entities\User', 'id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne('Modules\Category\Entities\BusinessCategory', 'id', 'category_id')->withTrashed();
    }

    public function subCategory()
    {
        return $this->hasOne('Modules\Category\Entities\BusinessCategory', 'id', 'subcategory_id')->withTrashed();
    }

    public function enquiryResult()
    {
        return $this->hasOne('Modules\Admin\Entities\EnquiryResult','enquiry_id','id');
    }
    

}