<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class Module extends Authenticatable
{
    protected $table = 'modules'; 
    protected $fillable = ['name', 'image', 'is_active'];
}