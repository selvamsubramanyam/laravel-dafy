<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;
class AdminUser extends Authenticatable
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'email', 'password','mobile','image','role','status'];

   

}