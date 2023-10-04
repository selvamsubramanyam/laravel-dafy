<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class ShopCountry extends Model
{
    protected $table = 'shop_countries';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'slug', 'is_active'];
}