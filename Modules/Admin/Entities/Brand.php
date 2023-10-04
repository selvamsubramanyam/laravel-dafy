<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $table = 'brands';
    protected $fillable = ['name','slug', 'logo','is_active'];
    protected $dates = ['deleted_at'];


    public function products()
    {
        return $this->hasMany("Modules\Product\Entities\Product","brand_id");
    }

    

}
