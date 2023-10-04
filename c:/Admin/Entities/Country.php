<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'country_code', 'flag_image', 'flag_image_128', 'telephone_code', 'status'];
}