<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
   // use SoftDeletes;
    protected $table = 'product_images';
    protected $primaryKey = 'id';
    protected $fillable =['product_id','image'];
    //protected $dates = ['deleted_at'];

    public function product()
    {
    	return $this->belongsTo('Modules\Product\Entities\Product','product_id');
    }

}
