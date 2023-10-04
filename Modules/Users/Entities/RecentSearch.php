<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecentSearch extends Authenticatable
{
    protected $table = 'recent_search';
    use SoftDeletes;

    protected $fillable = ['user_id', 'term', 'count'];
}