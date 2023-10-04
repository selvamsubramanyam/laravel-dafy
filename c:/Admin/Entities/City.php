<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'image', 'state_id', 'latitude', 'longitude', 'is_active'];

    public function getState()
    {
    	return $this->hasOne("Modules\Admin\Entities\State", "id", "state_id");
    }
}