<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyAddress extends Model
{
    // use SoftDeletes;
    protected $table = 'buy_addresses';
    
    protected $fillable = ['name', 'build_name', 'street', 'area', 'location', 'landmark', 'pincode', 'latitude', 'longitude', 'mobile', 'type'];
    
    // protected $dates = ['deleted_at'];
  
}
