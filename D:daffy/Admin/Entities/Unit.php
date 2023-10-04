<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'measurement_units';
    protected $fillable = ['name','slug','is_active'];

    public function products()
    {
        return $this->hasMany("Modules\Product\Entities\Product","brand_id");
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    
}
