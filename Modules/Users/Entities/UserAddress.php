<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Authenticatable
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'id';
    use SoftDeletes;
    
    protected $fillable = ['user_id', 'address_type', 'name', 'build_name', 'street', 'area', 'location', 'landmark', 'city', 'state_id', 'pincode', 'latitude', 'longitude', 'mobile', 'address_for', 'type', 'is_save', 'default'];

    public function getState()
    {
    	return $this->hasOne("Modules\Admin\Entities\State", "id", "state_id");
    }

    public function user()
    {
        return $this->belongsTo("Modules\Users\Entities\User");
    }

    public function state()
    {
        return $this->belongsTo("Modules\Admin\Entities\State",'state_id');
    }
}