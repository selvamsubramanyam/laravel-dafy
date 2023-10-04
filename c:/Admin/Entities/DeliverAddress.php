<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliverAddress extends Model
{
    // use SoftDeletes;
    protected $table = 'deliver_addresses';
    
    protected $fillable = ['name', 'build_name', 'street', 'area', 'location', 'landmark', 'pincode', 'latitude', 'longitude', 'mobile', 'type',];
    
    protected $dates = ['deleted_at'];
  
}
