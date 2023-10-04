<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductUntrackedImages extends Model
{
    protected $table = 'untracked_images';
    protected $fillable =['product_id','image'];

    public function untrackedProduct()
    {
    	return $this->belongsTo('Modules\Product\Entities\ProductUntracked','product_id');
    }

    public function untrackProduct()
    {
    	return $this->belongsTo('Modules\Product\Entities\ProductUntracked','product_id');
    }
}
