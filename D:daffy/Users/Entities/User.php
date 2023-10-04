<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    use SoftDeletes;
    
    protected $fillable = ['cc', 'mobile', 'name', 'business_name', 'email', 'business_email', 'steps', 'dob', 'latitude', 'longitude', 'location', 'area', 'build_name', 'city', 'district', 'state', 'landmark', 'address', 'city_id', 'blood_grp', 'type', 'password', 'image', 'business_image', 'wallet', 'is_vendor', 'user_code', 'qr_code', 'referral_by', 'role_id', 'is_active' ,'otp_status'];

    public function getCity()
    {
    	return $this->hasOne("Modules\Admin\Entities\City", "id", "city_id");
    }

    public function userAddresses()
    {
        return $this->hasMany("Modules\Users\Entities\UserAddress","user_id");
    }

    public function roles()
    {
        return $this->belongsToMany('Modules\Users\Entities\Role','user_role','user_id','role_id')->withTimestamps(); 
    }

    public function categories()
    {
        return $this->belongsToMany('Modules\Category\Entities\BusinessCategory','business_seller_categories','user_id','main_category_id')->withTimestamps(); 
    }

    public function shop()
    {
        return $this->hasOne('Modules\Shop\Entities\BusinessShop','seller_id');
    }
    
    
}