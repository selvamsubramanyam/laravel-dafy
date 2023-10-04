<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otp';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'otp', 'status', 'cc', 'edit_mobile'];
}