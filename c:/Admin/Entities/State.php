<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'country_id', 'latitude', 'longitude', 'is_active'];

    public function getCountry()
    {
    	return $this->hasOne("Modules\Admin\Entities\ShopCountry", "id", "country_id");
    }

    public function users()
    {
    	return $this->hasMany("Modules\Users\Entities\UserAddress","id","sate_id");
    }
}