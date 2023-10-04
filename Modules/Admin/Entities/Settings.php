<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';

    protected $fillable = ['label', 'slug', 'min_value', 'value', 'min_order_price', 'max_order_price', 'price', 'contact', 'description'];
}