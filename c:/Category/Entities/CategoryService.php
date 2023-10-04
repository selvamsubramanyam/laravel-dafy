<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class CategoryService extends Authenticatable
{
    protected $table = 'category_services';
    protected $fillable = ['category_id', 'service_names'];
}