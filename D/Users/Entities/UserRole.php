<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'role_id'];


    // public function userDetails()
    // {
    // 	return $this->belongsTo("Modules\Users\Entities\User","user_id");
    // }


    // public function roleDetails()
    // {
    // 	return $this->belongsTo("Modules\Admin\Entities\Role","role_id");
    // }
}