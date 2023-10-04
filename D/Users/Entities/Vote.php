<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vote extends Authenticatable
{
    protected $table = 'votes';
    protected $fillable = ['user_id', 'video_id'];
}