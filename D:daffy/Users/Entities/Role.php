<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'description','slug'];

    public function users()
    {
        return $this->belongsToMany('Modules\Users\Entities\User','user_role','role_id','user_id')->withTimestamps(); 
    }

    public function permissions() {
        return $this->belongsToMany("Modules\Users\Entities\Permission","role_has_permissions","role_id","permission_id");
    }

}