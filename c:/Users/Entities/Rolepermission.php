<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class Rolepermission extends Model
{
    protected $table = 'role_has_permissions';
    protected $primaryKey = 'id';
  
   public function permission()
    {
    	return $this->belongsTo("Modules\Users\Entities\Permission","permission_id");
    }
   
}