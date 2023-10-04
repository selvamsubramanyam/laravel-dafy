<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class Control extends Authenticatable
{
    protected $table = 'controls';
    protected $fillable = ['id', 'input', 'is_active'];
}