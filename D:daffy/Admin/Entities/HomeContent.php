<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    protected $table = 'home_contents';
    protected $fillable = ['slug', 'section', 'description', 'sub_heading1', 'sub_decription1', 'sub_heading2', 'sub_decription2', 'is_active'];   
}
