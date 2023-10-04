<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'districtid', 'is_active'];
}