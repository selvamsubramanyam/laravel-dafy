<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrendKeyword extends Authenticatable
{
    protected $table = 'trend_keywords';
    use SoftDeletes;

    protected $fillable = ['product_id', 'term', 'is_active'];
}