<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $table = 'user_devices';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'device_type', 'device_id', 'login_time', 'logout_time'];
}